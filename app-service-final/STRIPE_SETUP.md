# Configuration Stripe pour les Abonnements

Ce document explique comment configurer Stripe pour gérer les paiements d'abonnements dans l'application.

## Prérequis

1. Un compte Stripe (https://stripe.com)
2. Les clés API Stripe (publique et secrète)
3. Configuration du webhook Stripe

## Installation

### Backend (PHP/Symfony)

1. Installer le SDK Stripe PHP :
```bash
cd blog-celestin
composer require stripe/stripe-php
```

2. Exécuter la migration pour ajouter les champs Stripe :
```bash
php bin/console doctrine:migrations:migrate
```

### Frontend (React)

1. Installer le SDK Stripe JS :
```bash
cd celestin-responsive-blog
npm install @stripe/stripe-js
```

## Configuration

### Variables d'environnement Backend

Ajouter dans `.env` du backend (`blog-celestin/.env`) :

```env
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

### Variables d'environnement Frontend

Ajouter dans `.env` du frontend (`celestin-responsive-blog/.env`) :

```env
VITE_STRIPE_PUBLIC_KEY=pk_test_...
```

## Configuration du Webhook Stripe

1. Aller sur https://dashboard.stripe.com/webhooks
2. Cliquer sur "Add endpoint"
3. URL : `https://votre-domaine.com/api/stripe/webhook`
4. Événements à écouter :
   - `checkout.session.completed`
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`

5. Copier le "Signing secret" et l'ajouter dans `.env` comme `STRIPE_WEBHOOK_SECRET`

## Utilisation

### Dans les composants React

```jsx
import { StripeCheckout } from '../../components/StripeCheckout';

<StripeCheckout
  planKey="longTerm"
  planName="Plan Professionnel"
  planDuration="12 mois"
  planPrice="3 900,00 €"
  quantity={1}
  clientEmail="client@example.com"
  onSuccess={(data) => {
    console.log('Paiement réussi:', data);
  }}
  onError={(error) => {
    console.error('Erreur:', error);
  }}
/>
```

## Endpoints API

### Créer une session de checkout

**POST** `/api/stripe/create-checkout-session`

Body:
```json
{
  "planKey": "longTerm",
  "planName": "Plan Professionnel",
  "planDuration": "12 mois",
  "planPrice": "3 900,00 €",
  "quantity": 1,
  "clientEmail": "client@example.com"
}
```

Response:
```json
{
  "success": true,
  "sessionId": "cs_test_...",
  "url": "https://checkout.stripe.com/...",
  "pricingPlanId": 123
}
```

### Webhook Stripe

**POST** `/api/stripe/webhook`

Ce endpoint est appelé automatiquement par Stripe pour notifier les événements de paiement.

## Structure de la base de données

L'entité `PricingPlan` a été étendue avec les champs suivants :

- `stripe_customer_id` : ID du client Stripe
- `stripe_subscription_id` : ID de l'abonnement Stripe
- `stripe_payment_intent_id` : ID de l'intention de paiement
- `stripe_checkout_session_id` : ID de la session de checkout

## Statuts des plans

- `pending` : Plan créé, en attente de paiement
- `active` : Abonnement actif et payé
- `canceled` : Abonnement annulé
- `payment_failed` : Échec du paiement

## Mode Test vs Production

### Mode Test
- Utiliser les clés avec préfixe `sk_test_` et `pk_test_`
- Utiliser les cartes de test Stripe : https://stripe.com/docs/testing

### Mode Production
- Utiliser les clés avec préfixe `sk_live_` et `pk_live_`
- Mettre à jour les variables d'environnement
- Configurer le webhook en production

## Dépannage

### Erreur "Stripe n'a pas pu être chargé"
- Vérifier que `VITE_STRIPE_PUBLIC_KEY` est défini dans `.env`
- Vérifier que la clé publique commence par `pk_`

### Erreur "Invalid signature" dans le webhook
- Vérifier que `STRIPE_WEBHOOK_SECRET` est correct
- Vérifier que l'URL du webhook correspond exactement

### Le paiement ne se termine pas
- Vérifier les logs du backend
- Vérifier les événements dans le dashboard Stripe
- Vérifier que le webhook est bien configuré

## Support

Pour plus d'informations, consulter :
- Documentation Stripe : https://stripe.com/docs
- Documentation Stripe Subscriptions : https://stripe.com/docs/billing/subscriptions/overview

