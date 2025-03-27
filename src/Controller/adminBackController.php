<?php
namespace App\Controller;

use DateTime;
use App\Service\FormationService;
use App\Service\PlaylistService;

use App\Entity\Categorie;
use App\Entity\Playlist;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

class adminBackController extends AbstractController
{


    /**
      * 
      * @var FormationRepository
      */
    private $formationRepository;

    private $entityManager;
 
     /**
      * 
      * @var CategorieRepository
      */
    private $categorieRepository;

     /**
      * 
      * @var CONTROLLER_NAME
      */
    private $CONTROLLER_NAME;

    private $playlistRepository;

    private $FORMATIONS_TWIG_PATH;
    private $FORMATION_TWIG_PATH;
    private $CATEGORIES_TWIG_PATH;

    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository, PlaylistRepository $playlistRepository,EntityManagerInterface $entityManager) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
        $this->FORMATIONS_TWIG_PATH = "pages/formations.html.twig";
        $this->CATEGORIES_TWIG_PATH = "pages/admin_pages/categories.html.twig";
        $this->FORMATION_TWIG_PATH = "pages/admin_pages/admin_formation.html.twig";
        $this->PLAYLISTS_TWIG_PATH = "pages/admin_pages/playlists.html.twig";
        $this->ADMIN_ACCUEIL_TWIG_PATH = "adminbase.html.twig";
        $this->CONTROLLER_NAME = "adminBackController";
        $this->entityManager = $entityManager;
    }


    #[Route('/admin', name: 'admin.accueil')]
    public function index(): Response
    {

        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->ADMIN_ACCUEIL_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME
        ]);
    }

    #[Route('/admin/formations', name: 'admin.formations')]
    public function formations_page(): Response
    {

        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME
        ]);
    }


    #[Route('/admin/categories', name: 'admin.categories')]
    public function categories_page(): Response
    {       
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->CATEGORIES_TWIG_PATH, [
            'categories' => $categories,
            'formations' => $formations,
            'controller_name' => $this->CONTROLLER_NAME
        ]);
    }


    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function playlists_page(): Response
    {       
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->PLAYLISTS_TWIG_PATH, [
            'playlists' => $playlists,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME
        ]);
    }


    #[Route('/admin/nouvelle_categorie', name: 'admin.nouvelle_categorie')]
    public function nouvelleCategorie(Request $request): Response
    {



        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('nouvelle_categorie', $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.categories');
        }

        $this->categorie = new Categorie();
        $this->categorie->setName($request->get("nom_nouvelle_categorie"));


        $this->categorieRepository->add($this->categorie);

        $this->addFlash('success', 'Catégorie ajoutée avec succès.');

        return $this->redirectToRoute('admin.categories');
    }




    #[Route('/admin/modifier_categorie/{id}', name: 'admin.modifier_categorie')]
    public function modifierCategorie(Request $request,int $id): Response
    {


        $categories = $this->categorieRepository;
        $name = $request->get("nom_categorie");

        if( strlen($name) == 0 || is_null($id) ) return $this->redirectToRoute('admin.categories');

 
        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('modifier_categorie_'.$id, $request->request->get('_token'))) {

            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.categories');
        }

        $this->categorie = $this->categorieRepository->findOneBy(['id' => $id]);


        // Vérification de l'id
        if ( !$this->categorie ) {
            return $this->redirectToRoute('admin.categories');
        }

        $this->categorie->setName($name);


        $this->categorieRepository->add($this->categorie);

        $this->addFlash('success', 'Catégorie modifiée avec succès.');

        return $this->redirectToRoute('admin.categories');
    }

    
    #[Route('/admin/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME
        ]);
    }     

    #[Route('/admin/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->FORMATIONS_TWIG_PATH, [
            'formations' => $formations,
            'categories' => $categories,
            'controller_name' => $this->CONTROLLER_NAME,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  




    #[Route('/admin/formation/modifier_formation', name: 'admin.modifier_save_formations', methods: ['POST'])]
    public function modifierFormation(Request $request, FormationService $formationService): Response {

        $parametres = [
        "date" => $request->get("date"),
        "titre" => $request->get("formation_title"),
        "id" => $request->get("formation_id"),
        "description" => $request->get("formation_description", ""),
        "playlist" => $request->get("playlist_formation", ""),
        "url" => $request->get("formation_videoid", ""),
        "categories" => $request->get("formation_categories", [])
        ];

        if (isset($parametres["id"]) && $parametres["id"]) {

            $id = $parametres["id"];

            if (!$this->isCsrfTokenValid('enregistrer_formation_' . $id, $request->request->get('_token'))) {
                $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.formations');
            }
        $this->addFlash('success', 'Formation modifiée avec succès.');
        $formationService->updateFormation($id, $parametres);
        }

        

        return $this->redirectToRoute('admin.formations');
    }

    #[Route('/admin/formation/{id}', name: 'admin.formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);

        return $this->render($this->FORMATION_TWIG_PATH, [
            'creating' => false,
            'formation' => $formation,
            'categories' => $this->categorieRepository,
            'toutescategories' => $this->categorieRepository->findAllCategories(),
            'toutesplaylists' => $this->playlistRepository->findAll(),
            'playlist' =>$this->playlistRepository
        ]);
    }   





    #[Route('/admin/ajouter_formation/', name: 'admin.creer_formations')]
    public function formations_gestion(): Response{
        // $formation = $this->formationRepository->find($id);
        return $this->render($this->FORMATION_TWIG_PATH, [
            'creating' => true,
            'categories' => $this->categorieRepository->findAllCategories(),
            'toutesplaylists' => $this->playlistRepository->findAll(),
            'playlist' =>$this->playlistRepository
        ]);
    }   

    // #[Route('/admin/ajouter_formation/modifier_formation', name: 'admin.creer_save_formations', methods: ['POST'])]
    // public function save_ajouter_Formation(Request $request): Response{


    //     $parametres = [
    //     "date" => $request->get("date") ?? null,
    //     "titre" =>  $request->get("formation_title") ?? null,
    //     "id" =>  $request->get("formation_id") ?? null,
    //     "description" =>  $request->get("formation_description") ?? "",
    //     "playlist" =>  $request->get("playlist_formation") ?? "",
    //     "url" =>  $request->get("formation_videoid") ?? "",
    //     "categories" =>  $request->get("formation_categories") ?? array()];


    //     if( !isset($parametres["id"]) ){

    //         $this->save_data($parametres);
    //     }
    //     else{
            
    //         $this->update_data($parametres);
    //     }



    //     $this->addFlash('success', 'Formation crée avec succès.');
    //     // return $this->redirectToRoute('admin.formations');
    // }   


    #[Route('/admin/ajouter_formation/nouvelle_formation', name: 'admin.creer_save_formations', methods: ['POST'])]
    public function saveAjouterFormation(Request $request, FormationService $formationService): Response
    {
        $parametres = [
            "date" => $request->get("date"),
            "titre" => $request->get("formation_title"),
            "id" => $request->get("formation_id"),
            "description" => $request->get("formation_description", ""),
            "playlist" => $request->get("playlist_formation", ""),
            "url" => $request->get("formation_videoid", ""),
            "categories" => $request->get("formation_categories", [])
        ];

        if (!$parametres["id"]) {
            if (!$this->isCsrfTokenValid('enregistrer_formation', $request->request->get('_token'))) {
                $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.formations');
            }
            $formationService->saveFormation($parametres);
        } else {
            $this->addFlash('error', 'La formation n\'a pas pu être créée.');
        }

        return $this->redirectToRoute('admin.formations');
    }

    #[Route('/admin/playlists/nouvelle_playlist/', name: 'admin.playlists.nouvelle', methods: ['POST'])]
    public function nouvellePlaylist(Request $request): Response
    {
       
        $nom = $request->get("name");

        if ($nom) {
            if (!$this->isCsrfTokenValid('nouvelle_playlist', $request->request->get('_token'))) {
                $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.playlists');
            }

            $this->playlist = new Playlist();
            $this->playlist->setName($nom);
            $this->playlistRepository->add($this->playlist);

            $this->addFlash('success', 'Playlist ajoutée avec succès.');

        } else {
            $this->addFlash('error', 'La playlist n\'a pas pu être créée.');
        }

        return $this->redirectToRoute('admin.playlists');
    }







    #[Route('/admin/formations/suppr/{id}', name: 'admin.formations.suppr', methods: ['GET','POST'])]
    public function supprimerFormation(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);

        if (!$formation) {
            $this->addFlash('danger', 'Formation non trouvée.');
            return $this->redirectToRoute('admin.formations');
        }

        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('delete_formation_' . $id, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.formations');
        }

        $this->formationRepository->remove($formation);

        $this->addFlash('success', 'Formation supprimée avec succès.');

        return $this->redirectToRoute('admin.formations');
    }


    #[Route('/admin/categories/suppr/{id}', name: 'admin.categories.suppr', methods: ['GET','POST'])]
    public function supprimerCategorie(int $id, Request $request): Response
    {
        $categorie = $this->categorieRepository->find($id);

        if (!$categorie) {
            $this->addFlash('danger', 'Catégorie non trouvée.');
            return $this->redirectToRoute('admin.categories');
        }

        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('delete_categorie_' . $id, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.categories');
        }

        $this->categorieRepository->remove($categorie);

        $this->addFlash('success', 'Catégorie supprimée avec succès.');

        return $this->redirectToRoute('admin.categories');
    }

    #[Route('/admin/playlists/suppr/{id}', name: 'admin.playlists.suppr', methods: ['GET','POST'])]
    public function supprimerPlaylist(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);


        if (!$playlist) {
            $this->addFlash('danger', 'PLaylist non trouvée.');
            return $this->redirectToRoute('admin.playlists');
        }

        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('delete_playlist_' . $id, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin.playlists');
        }


        $formations_concernees = $this->formationRepository->findAllForOnePlaylist($playlist);
        if (count($formations_concernees) > 0) {
            $this->addFlash('danger', 'Impossible de supprimer une playlist contenant des formations');
        } else {
            $this->playlistRepository->remove($playlist);
            $this->addFlash('success', 'Playlist supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.playlists');
    }


    #[Route('/admin/enregistrer_playlist/', name: 'admin.enregistrer_playlist')]
    public function enregistrerPlaylist(Request $request): Response
    {


        $nom = $request->get("nom");
        $id = $request->get("id");
        $categories = $request->get("playlist_categorie", []);
        $description = $request->get("description");
        $token = $request->get("_token");

        $params = [
            "nom" => $nom,
            "id" => $id,
            "description" => $description,
        ];

        echo $token;

        //Création 

        if($id == null){

            if (!$this->isCsrfTokenValid('playlist_token_',$token)) {

                $this->addFlash('danger', 'Token CSRF invalide.');
                return $this->redirectToRoute('admin.playlists');
            }
            //Ajout
            else{

                $this->addFlash('sucess', 'Playlist ajoutée.');
                $playlist = new Playlist();
                
                // Définition des propriétés de base
                $playlist->setName($params['nom']);
                $playlist->setDescription($params['description'] ?? '');

                // Enregistrement en base de données
                $this->entityManager->persist($playlist);
                $this->entityManager->flush();
                return $this->redirectToRoute('admin.playlists');
            }

        }
        else{


            if (!$this->isCsrfTokenValid('playlist_token_'.$id,$token)) {
        // echo $a;

                $this->addFlash('danger', 'Token CSRF invalide.');
                return $this->redirectToRoute('admin.playlists');
            }
            //Edit
            else{

                $this->addFlash('success', 'Playlist modifiée avec succès.');
                $playlist = $this->playlistRepository->find($id);

                if (!$playlist) {
                    $this->addFlash('danger', 'Modification impossible.');
                }

                // Mise à jour des propriétés de base
                $playlist->setName($params['nom']);
                $playlist->setDescription($params['description'] ?? '');

                // Enregistrement des modifications en base de données
                $this->entityManager->persist($playlist);
                $this->entityManager->flush();
                
                return $this->redirectToRoute('admin.playlists');
            }

        }

        //Modification


        // $categories = $this->categorieRepository;
        // $name = $request->get("nom_categorie");

        // if( strlen($name) == 0 || is_null($id) ) return $this->redirectToRoute('admin.categories');

 
        // // Vérification du token CSRF
        // if (!$this->isCsrfTokenValid('modifier_categorie_'.$id, $request->request->get('_token'))) {

        //     $this->addFlash('danger', 'Token CSRF invalide.');
        //     return $this->redirectToRoute('admin.categories');
        // }

        // $this->categorie = $this->categorieRepository->findOneBy(['id' => $id]);


        // // Vérification de l'id
        // if ( !$this->categorie ) {
        //     return $this->redirectToRoute('admin.categories');
        // }

        // $this->categorie->setName($name);


        // $this->categorieRepository->add($this->categorie);
        $this->addFlash('error', 'Ajout Impossible');


        return $this->redirectToRoute('admin.playlists');
    }




    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function playlists_sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "formations":
                $playlists = $this->playlistRepository->findAllOrderByFormation($ordre);
            default:
                $playlists = $this->playlistRepository->findAllOrderByName("ASC");
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->PLAYLISTS_TWIG_PATH, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }          

    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function playlists_findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->PLAYLISTS_TWIG_PATH, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    #[Route('/admin/playlists/editer/{id}', name: 'admin.playlists.editer')]
    public function playlists_showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/admin_pages/playlist.html.twig", [
            'playlist' => $playlist,
            'categories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
        ]);        
    }       












}