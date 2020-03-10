<?php
// src/Controller/VetementController.php
namespace App\Controller;

// ...
use App\Entity\Vetement;
use App\Entity\Couleur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VetementController extends AbstractController
{
    /**
     * @Route("/{token}/vetement/insert/{nom}/{idCouleur}", name="creer_vetement")
     */
    public function creerVetement(ValidatorInterface $validator, EntityManagerInterface $entityManager, $token, $nom, $idCouleur = 0): Response
    {
        #$entityManager = $this->getDoctrine()->getManager();
        $vetement = new Vetement();
        $vetement->setNom($nom);
        if($idCouleur == 0) {
            $idCouleur = random_int(1, 4);
        }
        // Récupération du dépôt de requete de Couleur
        $repCouleur = $this->getDoctrine()->getRepository(Couleur::Class);
        $couleur = $repCouleur->find($idCouleur);
        // Affectation à Vetement
        $vetement->setCouleur($couleur);

        // Validation des données
        $this->validationObjet($validator, $vetement);

        // On sauvegarde l'objet vetement
        $entityManager->persist($vetement);

        // Execution de la requete
        $entityManager->flush();

        // Encodage du vetement en json
        $response = new Response();
        $response->setContent(json_encode([
            'opération' => 'insert',
            'résultat' => true,
            'type_objet' => 'vetement',
            'id_objet' => $vetement->getId(),
        ]));
        $response->headers->set('Content-Type', 'application/json');
        # voir aussi use Symfony\Component\HttpFoundation\JsonResponse;
        return $response;
    }

    private function validationObjet($validator, Vetement $vet)
    {
        $errors = $validator->validate($vet);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
    }
}