<?php
// src/Controller/SuspectController.php
namespace App\Controller;

// ...
use App\Entity\Suspect;
use App\Entity\Couleur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SuspectController extends AbstractController
{
    /**
     * @Route("/{token}/suspect/insert/{nom}/{age}/{email}/{genre}", name="creer_suspect")
     */
    public function creerSuspect(ValidatorInterface $validator, EntityManagerInterface $entityManager, $token, $nom, $age, $email, $genre): Response
    {
        #$entityManager = $this->getDoctrine()->getManager();
        $Suspect = new Suspect();
        $Suspect->setNom($nom);
        $Suspect->setAge($age);
        $Suspect->setEmail($email);
        $Suspect->setGenre($genre);
        // Validation des données
        $this->validationObjet($validator, $Suspect);
        // On sauvegarde l'objet Suspect
        $entityManager->persist($Suspect);

        // Execution de la requete
        $entityManager->flush();

        // Encodage du Suspect en json
        $response = new Response();
        $response->setContent(json_encode([
            'opération' => 'insert',
            'résultat' => true,
            'type_objet' => 'Suspect',
            'id_objet' => $Suspect->getId(),
        ]));
        $response->headers->set('Content-Type', 'application/json');
        # voir aussi use Symfony\Component\HttpFoundation\JsonResponse;
        return $response;
    }

    private function validationObjet($validator, Suspect $vet)
    {
        $errors = $validator->validate($vet);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
    }

    /**
     * @Route("/{token}/select/suspect/", name="all_suspect")
     */
    public function SelectSuspect(EntityManagerInterface $entityManager, $token): Response
    {

    $encoders = [new XmlEncoder(), new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);

    // Récupération du dépôt de requete de Suspect
    $repSuspect = $this->getDoctrine()->getRepository(Suspect::Class);
    $listSuspect = $repSuspect->findAll();

    // Création du JSON
    $jsonContent = $serializer->serialize($listSuspect, 'json');

    // Encodage du vetement en json
    $response = new Response();
    $response->setContent($jsonContent);

    $response->headers->set('Content-Type', 'application/json');

    return $response;
    }
}