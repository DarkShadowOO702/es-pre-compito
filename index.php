<?php
// Variabili del form - inizializzazzione
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

// Controllo se il form è stato inviato
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
    }
}


?>