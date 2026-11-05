<?php

// Variabili del form
$prodotto = "";
$prezzo = "";
$sconto = "";

// Variabili per gli errori
$erroreProdotto = "";
$errorePrezzo = "";
$erroreSconto = "";

//Variabili del risultato 
$mostraRisultato = false;
$prezzoFinale = 0;
$risparmio = 0;
$fascia = "";
$colore = "";

//Controllo se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    // Recupero dati inseriti
    $prodotto = trim($_POST["prodotto"]);
    $prezzo = trim($_POST["prezzo"]);
    $sconto = trim($_POST["sconto"]);

    // CONTROLLO PRODOTTO
    if ($prodotto == ""){
        $erroreProdotto = "Inserisci il nome del prodotto: ";
    }else if (strlen($prodotto) > 30){
        $erroreProdotto = "Il prodotto può avere massimo 30 caratteri. ";
    }

    // CONTROLLO PREZZO
    if ($prezzo == ""){
        $errorePrezzo = "Inserisci un prezzo: ";
    }else if(!is_numeric($prezzo) || $prezzo <= 0){
        $errorePrezzo = "Il prezzo deve essere maggiore di 0. ";
    }

    // CONTROLLO SCONTO
    if ($sconto == ""){
        $erroreSconto = "Inserisci lo sconto: ";
    }else if ($sconto != (int)$sconto || $sconto < 1 || $sconto > 90){
        $erroreSconto = "Lo sconto deve essere compreso tra 1 e 90";
    }else if(!is_numeric($sconto)){
        $erroreSconto = "Lo sconto deve essere compreso tra 1 e 90 ";
    }

    // CONTROLLO SE NON CI SONO ERRORI
    if ($erroreProdotto == "" && $errorePrezzo == "" && $erroreSconto == ""){

        // Conversione dei valori
        $prezzo = (float) $prezzo;
        $sconto = (int) $sconto;

        // Calcolo del risparmio
        $risparmio = $prezzo * $sconto / 100;

        // Calcolo del prezzo finale
        $prezzoFinale = $prezzo - $risparmio;

        // Controllo della fascia 
        if($sconto < 20){

            $fascia = "PICCOLO";
            $colore = "#DCEBFF";

        }else if ($sconto < 50){

            $fascia = "BUONO";
            $colore = "#DDF5E3";

        }else{

            $fascia ="SUPER";
            $colore = "FFE6B8";

        }

        $mostraRisultato = true;

        // SALVATAGGIO NEL FILE CSV
        $file = fopen("log.csv", "a");

        if ($file != false){

            // Se il file e' vuoto, scrivo l'intestazione
            if (filesize("log.csv") == 0){

                $intestazione = array(
                    "Data",
                    "Prodotto",
                    "Prezzo",
                    "Sconto",
                    "Prezzo finale",
                    "Risparmio",
                    "Fascia",
                    "Esito",
                );

                fputcsv($file, $intestazione, "i");
            }

            // Creo l'array con i dati
            $dati = array(
                date("d/m/Y H:i:s"),
                $prodotto,
                number_format($prezzo,2,".",""),
                $sconto,
                number_format($prezzoFinale,2,".",""),
                number_format($risparmio,2,".",""),
                $fascia,
                "OK"
            );

            // Salvo i dati nel file
            fputcsv($file, $dati, ";");

            fclose($file);
         }        
    }
}

// LETTURA DELLO STORICO 

$storico = array();

if(file_exists("log.csv")){

    $file = fopen("log.csv", "r");

    if($file != false){

        while (($riga = fgetcsv($file, 1000, ";")) !== false){

            // Inserisco nello storico solo i calcoli corretti
            if (count($riga) == 8 && $riga[7] == "OK"){

                $storico[] = $riga;
            }
        }

        fclose($file);
    }
}


?>