<?php
namespace App\Controller;

use DateTime;
use App\Service\FormationService;

use App\Entity\Categorie;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class adminBackController extends AbstractController
{


    /**
      * 
      * @var FormationRepository
      */
    private $formationRepository;
 
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

    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository, PlaylistRepository $playlistRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
        $this->FORMATIONS_TWIG_PATH = "pages/formations.html.twig";
        $this->CATEGORIES_TWIG_PATH = "pages/admin_pages/categories.html.twig";
        $this->FORMATION_TWIG_PATH = "pages/admin_pages/admin_formation.html.twig";
        $this->ADMIN_ACCUEIL_TWIG_PATH = "adminbase.html.twig";
        $this->CONTROLLER_NAME = "adminBackController";
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
            'formations' => $formations,
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
    public function createFormation(): Response{
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
            $this->addFlash('danger', 'Formation non trouvée.');
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










}