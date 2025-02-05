<?php

namespace App\Services;


/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */



use PhpOffice\PhpWord\TemplateProcessor;

class CustomTemplateProcessor extends TemplateProcessor
{

    public function setTrackRevisions($trackRevisions = true): void
    {
        $string = $trackRevisions ? 'true' : 'false';
        $matches = [];
        if (preg_match('/<w:trackRevisions w:val=\"(true|false|1|0|on|off)\"\/>/', $this->tempDocumentSettingsPart, $matches)) {
            $this->tempDocumentSettingsPart = str_replace($matches[0], '<w:trackRevisions w:val="' . $string . '"/>', $this->tempDocumentSettingsPart);
        } else {
            $this->tempDocumentSettingsPart = str_replace('</w:settings>', '<w:trackRevisions w:val="' . $string . '"/></w:settings>', $this->tempDocumentSettingsPart);
        }
    }


    public function hasVariables($variableNames)
    {
        $variables = $this->getVariablesForPart($this->tempDocumentMainPart);

        foreach ($this->tempDocumentHeaders as $headerXML) {
            $variables = array_merge(
                $variables,
                $this->getVariablesForPart($headerXML)
            );
        }

        foreach ($this->tempDocumentFooters as $footerXML) {
            $variables = array_merge(
                $variables,
                $this->getVariablesForPart($footerXML)
            );
        }

        foreach ($variableNames as $variableName) {
            if (in_array($variableName, $variables)) {
                continue;
            } else {
                return false;
            }
        }

        return true;
    }
}
