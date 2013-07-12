<?php

namespace DependenSees\Helper;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class TableHelper
{
    protected $headerCellFormat;
    protected $rowCellFormat;
    protected $table;
    protected $maxWidth;

    public function __construct($table)
    {
        $this->headerCellFormat = '<info>%s</info>';
        $this->rowCellFormat = '<comment>%s</comment>';
        $this->table = $table;
        $this->maxCellWidth();
    }

    public function render($output)
    {
        $this->renderheader($output);
        $this->renderSeparator($output);
        $this->renderRows($output);
    }

    protected function maxCellWidth()
    {
        $this->maxWidth = array();
        foreach ($this->table['header'] as $key => $cell) {
            $this->maxWidth[$key] = isset($this->maxWidth[$key]) ? max($this->maxWidth[$key], strlen($cell)) : strlen($cell);
        }

        foreach ($this->table['rows'] as $row) {
            foreach ($row as $key => $cell) {
                $this->maxWidth[$key] = isset($this->maxWidth[$key]) ? max($this->maxWidth[$key], strlen($cell)) : strlen($cell);
            }
        }
    }

    protected function renderheader($output)
    {
        $render = array();

        foreach ($this->table['header'] as $key => $cell) {
            $render[$key] = str_pad($cell, $this->maxWidth[$key]);
            $render[$key] = sprintf($this->headerCellFormat, $render[$key]);
        }

        $output->writeLn(implode(' | ', $render));
    }

    protected function renderRows($output)
    {
        foreach ($this->table['rows'] as $row) {
            $render = array();

            foreach ($row as $key => $cell) {
                $render[$key] = str_pad($cell, $this->maxWidth[$key]);
                $render[$key] = sprintf($this->rowCellFormat, $render[$key]);
            }

            $output->writeLn(implode(' | ', $render));
        }
    }

    protected function renderSeparator($output)
    {
        $render = array();

        foreach ($this->maxWidth as $key => $width) {
            $render[$key] = str_repeat('-', $width);
        }

        $output->writeLn(implode('-+-', $render));
    }
}