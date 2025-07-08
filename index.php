<html lang="fr">
 <head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Bankiko - Banque Malagasy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
 </head>
 <body class="bg-white text-gray-900">

 <?php include 'header.php'; ?>

 <!-- Section Hero -->
 <section class="relative text-white px-6 sm:px-10 md:px-16 py-16 md:py-24 max-w-7xl mx-auto" style="background-color: #2c3e50;">
   <div class="max-w-lg">
    <h1 class="text-3xl sm:text-4xl font-semibold leading-tight mb-2">
     Le bleu vous
     <br/>
     va à merveille
    </h1>
    <h2 class="text-lg font-semibold mb-4">
     Bienvenue chez Bankiko
     <br/>
     La banque des Malgaches
    </h2>
    <p class="text-xs leading-tight mb-6">
     Nous sommes heureux de vous accueillir et de continuer à vous accompagner chaque jour. Retrouvez vos agences habituelles, avec les mêmes équipes, produits et services que vous connaissez déjà.
    </p>
    <p class="text-xs leading-tight">
     Comptez sur notre engagement à vos côtés, à tout moment !
    </p>
   </div>
   <div class="absolute right-10 top-1/2 -translate-y-1/2 flex flex-col space-y-1 cursor-pointer">
    <i class="fas fa-chevron-down text-white text-xl"></i>
    <i class="fas fa-chevron-down text-white text-xl"></i>
   </div>
 </section>

 <!-- Contenu principal -->
 <main class="max-w-7xl mx-auto px-6 sm:px-10 md:px-16 mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
   <!-- Section Actualités -->
   <section class="md:col-span-1 text-blue-700 text-sm font-normal">
    <p class="uppercase font-semibold mb-1">ACTUALITÉS</p>
    <h3 class="font-semibold mb-3 leading-snug">
     Carte bancaire + chéquier : le duo gagnant pour vos paiements
    </h3>
    <button class="text-blue-700 text-xs font-normal border border-blue-700 rounded px-3 py-1 hover:bg-blue-50" type="button">
     Contacter un conseiller
    </button>
   </section>

   <!-- Section FAQ -->
   <section class="md:col-span-2 text-blue-700 text-sm font-normal max-w-md">
    <p class="uppercase font-semibold mb-1">FAQ</p>
    <h3 class="font-semibold mb-2">
     Vous souhaitez en savoir plus sur cette transition ?
    </h3>
    <p class="text-xs mb-6 text-gray-600">
     Retrouvez ici les réponses aux questions fréquemment posées.
    </p>
    <div class="space-y-4">
     <details class="border-b border-gray-200 pb-2">
      <summary class="cursor-pointer font-semibold text-xs flex justify-between items-center">
       Quelles sont les activités bancaires concernées par ce changement ?
       <i class="fas fa-chevron-down text-xs text-blue-700"></i>
      </summary>
      <p class="text-xs mt-2 text-gray-600 leading-tight">
       Cela concerne toutes les activités bancaires : paiements, opérations financières, services aux entreprises et aux particuliers.
      </p>
     </details>
     <details class="border-b border-gray-200 pb-2">
      <summary class="cursor-pointer font-semibold text-xs flex justify-between items-center">
       Pourquoi Bankiko a-t-elle repris l'ancienne banque ?
       <i class="fas fa-chevron-down text-xs text-blue-700"></i>
      </summary>
      <p class="text-xs mt-2 text-gray-600 leading-tight">
       L’objectif est de renforcer l’offre bancaire à Madagascar et de proposer des services encore plus proches des Malgaches.
      </p>
     </details>
     <details class="border-b border-gray-200 pb-2">
      <summary class="cursor-pointer font-semibold text-xs flex justify-between items-center">
       Qu’est-ce qui distingue Bankiko ?
       <i class="fas fa-chevron-down text-xs text-blue-700"></i>
      </summary>
      <p class="text-xs mt-2 text-gray-600 leading-tight">
       Une banque proche des gens, qui offre des services rapides, simples et durables, en phase avec la langue et la culture malgaches.
      </p>
     </details>
    </div>
    <button class="mt-4 text-blue-700 text-xs font-normal border border-blue-700 rounded px-3 py-1 hover:bg-blue-50" type="button">
     Voir toutes les questions
    </button>
   </section>
 </main>

 <?php include 'footer.php'; ?>
 </body>
</html>
