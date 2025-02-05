<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Courier;
use App\Models\Document;

class DocumentNumberService
{

    private $courierCount;

    public function generateDocumentNumber(Document $document)
    {

        $this->courierCount = Courier::max("id") + 1;
        return $this->generateCourrierSortantNumber($document);
    }

    public function generateContractNumber()
    {
        $year = Carbon::now()->format('y');
        $documentNumber = $year . 'CT' . str_pad($this->courierCount, 6, '0', STR_PAD_LEFT);
        return $documentNumber;
    }


    public function generatePvNumber()
    {

        // Generate a 6-digit courier number with leading zeroes
        $courierNumber = str_pad($this->courierCount, 6, '0', STR_PAD_LEFT);

        return $courierNumber;
    }


    public function generateNoteServiceNumber()
    {

        $year = Carbon::now()->format('Y');
        $documentNumber = str_pad($this->courierCount, 3, '0', STR_PAD_LEFT) . '/' . $year;
        return $documentNumber;
    }

    public function generateCourrierSortantNumber(Document $document)
    {
        // Check if there are associated parapheurs
        $parapheurs = $document->parapheurs;

        if ($parapheurs->isEmpty()) {
            throw new \RuntimeException('No parapheurs associated with the document.');
        }

        $userFunctions = $document->parapheurs()->get()->map(fn($parapheur) => $parapheur->user->userFunction?->name);

        // Generate document number
        $documentNumber = str_pad($this->courierCount, 6, '0', STR_PAD_LEFT) . '/GAINDE2000/AG';

        // Append user functions to the document number in the same order
        foreach ($userFunctions as $function) {
            $documentNumber .= '/' . $function;
        }

        return $documentNumber;
    }


    // Additional methods
    public function formatRemainingTime($timeDifference, $prefix)
    {
        $formattedRemainingTime = $prefix;

        // Format days if greater than zero
        if ($timeDifference->d > 0) {
            $formattedRemainingTime .= $timeDifference->d . ' jour' . ($timeDifference->d > 1 ? 's' : '');
        }

        // Format hours if greater than zero
        if ($timeDifference->h > 0) {
            $formattedRemainingTime .= ($formattedRemainingTime ? ' ' : '') . $timeDifference->h . ' heure' . ($timeDifference->h > 1 ? 's' : '');
        }

        // Format minutes if greater than zero
        if ($timeDifference->i > 0) {
            $formattedRemainingTime .= ($formattedRemainingTime ? ' ' : '') . $timeDifference->i . ' minute' . ($timeDifference->i > 1 ? 's' : '');
        }

        return $formattedRemainingTime;
    }


    public function getColorBasedOnPercentage($totalMinutes, $recoveryDelay)
    {
        $percentageRemaining = ($totalMinutes / ($recoveryDelay * 60)) * 100;

        if ($percentageRemaining > 75) {
            return 'success'; // More than 75% remaining time
        } elseif ($percentageRemaining > 25) {
            return 'warning'; // Between 25% and 75% remaining time
        } else {
            return 'danger'; // Less than or equal to 25% remaining time
        }
    }

}
