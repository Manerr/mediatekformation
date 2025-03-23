<?php
namespace App\Service;

use App\Entity\Formation;
use App\Entity\Categorie;
use App\Entity\Playlist;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class FormationService
{
    private $formationRepository;
    private $categorieRepository;
    private $playlistRepository;
    private $entityManager;

    public function __construct(
        FormationRepository $formationRepository,
        CategorieRepository $categorieRepository,
        PlaylistRepository $playlistRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
        $this->entityManager = $entityManager;
    }

    public function saveFormation(array $parametres): Formation
    {
        $formation = new Formation();
        $formation->setTitle($parametres['titre']);
        $formation->setDescription($parametres['description']);
        $formation->setVideoId($parametres['url']);
        $formation->setPublishedAt(new DateTime($parametres['date']));

        // Gérer la playlist (récupérer ou créer)
        $playlist = $this->playlistRepository->findOneBy(['name' => $parametres['playlist']]) ?? new Playlist();
        $playlist->setName($parametres['playlist']);
        $this->entityManager->persist($playlist);
        $formation->setPlaylist($playlist);

        // Gérer les catégories
        foreach ($parametres['categories'] as $catName) {
            $categorie = $this->categorieRepository->findOneBy(['name' => $catName]) ?? new Categorie();
            $categorie->setName($catName);
            $this->entityManager->persist($categorie);
            $formation->addCategory($categorie);
        }

        // Sauvegarde en base
        $this->entityManager->persist($formation);
        $this->entityManager->flush();

        return $formation;
    }

    public function updateFormation(int $id, array $parametres): ?Formation{
    // Récupérer la formation existante
    $formation = $this->formationRepository->find($id);
    if (!$formation) {
        throw new \Exception("Formation introuvable.");
    }

    // Mise à jour des champs s'ils sont fournis
    if (!empty($parametres['titre'])) {
        $formation->setTitle($parametres['titre']);
    }
    if (!empty($parametres['description'])) {
        $formation->setDescription($parametres['description']);
    }
    if (!empty($parametres['url'])) {
        $formation->setVideoId($parametres['url']);
    }
    if (!empty($parametres['date'])) {
        try {
            $formation->setPublishedAt(new DateTime($parametres['date']));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Date invalide : " . $parametres['date']);
        }
    }

    // Mise à jour de la playlist existante (ne pas en créer de nouvelle)
    if (!empty($parametres['playlist'])) {
        $playlist = $this->playlistRepository->findOneBy(['name' => $parametres['playlist']]);
        if ($playlist) {
            $formation->setPlaylist($playlist);
        }
    }

    // Mise à jour des catégories (ne pas en créer de nouvelles)
    if (!empty($parametres['categories']) && is_array($parametres['categories'])) {
        // $formation->clearCategories(); // Supprime les anciennes catégories
        foreach ($parametres['categories'] as $catName) {
            $categorie = $this->categorieRepository->findOneBy(['name' => $catName]);
            if ($categorie) {
                $formation->addCategory($categorie);
            }
        }
    }

    // Sauvegarde en base
    $this->entityManager->flush();

    return $formation;
}




}