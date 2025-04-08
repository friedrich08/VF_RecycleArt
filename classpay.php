<?php

// Classe principale Payment
abstract class Payment {
    protected $cart;

    public function __construct(Cart $cart) {
        $this->cart = $cart;
    }

    // Méthode abstraite pour traiter le paiement
    abstract public function processPayment(array $data): string;
}

// Sous-classe TmoneyPayment
class TmoneyPayment extends Payment {
    public function processPayment(array $data): string {
        // Logique de traitement pour Tmoney
        if (isset($data['tmoney_phone']) && isset($data['tmoney_amount'])) {
            $phone = $data['tmoney_phone'];
            $amount = $data['tmoney_amount'];

            // Simuler un traitement de paiement
            if ($amount >= $this->cart->getTotal()) {
                return "Paiement avec Tmoney réussi pour le numéro $phone.";
            } else {
                return "Le montant spécifié ($amount) est insuffisant pour couvrir le total du panier ({$this->cart->getTotal()}).";
            }
        } else {
            return "Données manquantes pour le paiement Tmoney.";
        }
    }
}

// Sous-classe FloozPayment
class FloozPayment extends Payment {
    public function processPayment(array $data): string {
        // Logique de traitement pour Flooz
        if (isset($data['flooz_phone']) && isset($data['flooz_amount'])) {
            $phone = $data['flooz_phone'];
            $amount = $data['flooz_amount'];

            // Simuler un traitement de paiement
            if ($amount >= $this->cart->getTotal()) {
                return "Paiement avec Flooz réussi pour le numéro $phone.";
            } else {
                return "Le montant spécifié ($amount) est insuffisant pour couvrir le total du panier ({$this->cart->getTotal()}).";
            }
        } else {
            return "Données manquantes pour le paiement Flooz.";
        }
    }
}

// Sous-classe EcobankPayment
class EcobankPayment extends Payment {
    public function processPayment(array $data): string {
        // Logique de traitement pour Ecobank
        if (isset($data['ecobank_account']) && isset($data['ecobank_amount'])) {
            $account = $data['ecobank_account'];
            $amount = $data['ecobank_amount'];

            // Simuler un traitement de paiement
            if ($amount >= $this->cart->getTotal()) {
                return "Paiement avec Ecobank réussi pour le compte $account.";
            } else {
                return "Le montant spécifié ($amount) est insuffisant pour couvrir le total du panier ({$this->cart->getTotal()}).";
            }
        } else {
            return "Données manquantes pour le paiement Ecobank.";
        }
    }
}

// Classe Cart
class Cart {

    public function getTotalAmount() {
        $total = 0;
        if (isset($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return $total;
    }
    private $items = [];

    public function addItem($item, $price) {
        $this->items[] = ['item' => $item, 'price' => $price];
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'];
        }
        return $total;
    }
}

