<?php
/**
 * SettingsController controller de la configuration du Bundle Gestock
 * 
 * PHP Version 5
 * 
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    GIT: a4408b1f9fc87a1f93911d80e8421fef1bd96cab
 * @link       https://github.com/GLSR/glsr
 */

namespace Glsr\GestockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Glsr\GestockBundle\Entity\Settings;
use Glsr\GestockBundle\Entity\Tva;
use Glsr\GestockBundle\Entity\Company;
use Glsr\GestockBundle\Entity\FamilyLog;
use Glsr\GestockBundle\Entity\SubFamilyLog;
use Glsr\GestockBundle\Entity\ZoneStorage;
use Glsr\GestockBundle\Entity\UnitStorage;

use Glsr\GestockBundle\Form\SettingsType;
use Glsr\GestockBundle\Form\TvaType;
use Glsr\GestockBundle\Form\CompanyType;
use Glsr\GestockBundle\Form\FamilyLogType;
use Glsr\GestockBundle\Form\SubFamilyLogType;
use Glsr\GestockBundle\Form\ZoneStorageType;
use Glsr\GestockBundle\Form\UnitStorageType;

/**
 * class SettingsController
 * 
 * @category   Controller
 * @package    Gestock
 * @subpackage Settings
 */
class SettingsController extends Controller
{
    /**
     * showCompanyAction Affiche les données de l'entreprise
     * 
     * @return type
     */
    public function showCompanyAction()
    {
        $etm = $this->getDoctrine()->getManager();
        $repoCompany = $etm->getRepository('GlsrGestockBundle:Company');
        $company = $repoCompany->findAll();
        
        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:index.html.twig',
            array('company' => $company)
        );
    }
    
    /**
     * showApplicationAction Affiche les paramètres de base de l'application
     * 
     * @return type
     */
    public function showApplicationAction()
    {
        $etm = $this->getDoctrine()->getManager();
        $repoSettings = $etm->getRepository('GlsrGestockBundle:Settings');
        $settings = $repoSettings->findAll();
        
        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:index.html.twig',
            array('settings' => $settings)
        );
    }
    
    /**
     * showDiversAction Affiche les paramètres divers de l'application
     * 
     * @return type
     */
    public function showDiversAction()
    {
        $etm = $this->getDoctrine()->getManager();
        $subFamilyLog = $etm
            ->getRepository('GlsrGestockBundle:SubFamilyLog')
            ->findAll();
        
        $zoneStorage = $etm
            ->getRepository('GlsrGestockBundle:ZoneStorage')
            ->findAll();
        
        $unitStorage = $etm
            ->getRepository('GlsrGestockBundle:UnitStorage')
            ->findAll();
        
        $tva = $etm->getRepository('GlsrGestockBundle:Tva')->findAll();
        
        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:index.html.twig',
            array(
                'divers'       => 1,
                'subfamilylog' => $subFamilyLog,
                'zonestorage'  => $zoneStorage,
                'unitstorage'  => $unitStorage,
                'tva'          => $tva
            )
        );
    }
    
    /**
     * addSettingsAction 
     * 
     * @return type
     */
    public function addSettingsAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $settings = new Settings();
        
        $form = $this->createForm(new SettingsType(), $settings);
        
        // On récupère la requête
        $request = $this->getRequest();

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            $form->bind($request);

            // On vérifie que les valeurs rentrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $article dans la base de données
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($settings);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Configuration bien ajoutée');

                // On redirige vers la page de visualisation
                // des configuration de l'appli
                return $this->redirect($this->generateUrl('glstock_application'));
            }
        }

        // À ce stade :
        // - soit la requête est de type GET,
        // donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - soit la requête est de type POST,
        // mais le formulaire n'est pas valide, donc on l'affiche de nouveau

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * editSettingsAction Modifier les paramètres de base de l'application
     * 
     * @param \Glsr\GestockBundle\Entity\Settings $settings
     * Objet de l'entité Settings à modifier
     * 
     * @return type
     */
    public function editSettingsAction(Settings $settings)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        // On utilise le SettingsType
        $form = $this->createForm(new SettingsType(), $settings);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On enregistre la config
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($settings);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Configuration bien modifié');

                return $this->redirect($this->generateUrl('glstock_application'));
            }
        }

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:edit.html.twig',
            array(
            'form'    => $form->createView(),
            'settings' => $settings
            )
        );
    }

    /**
     * addCompanyAction crée les données de l'entreprise
     * 
     * @return type
     */
    public function addCompanyAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $company = new Company();
        
        $form = $this->createForm(new CompanyType(), $company);
        
        // On récupère la requête
        $request = $this->getRequest();

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            $form->bind($request);

            // On vérifie que les valeurs rentrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $article dans la base de données
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($company);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Company bien ajoutée');

                // On redirige vers la page de visualisation
                // des configuration de l'appli
                return $this->redirect($this->generateUrl('glstock_company'));
            }
        }

        // À ce stade :
        // - soit la requête est de type GET,
        // donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - soit la requête est de type POST,
        // mais le formulaire n'est pas valide, donc on l'affiche de nouveau

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * editCompanyAction Modifier l'entreprise
     * 
     * @param \Glsr\GestockBundle\Entity\Company $company Entreprise à modifier
     * 
     * @return type
     */
    public function editCompanyAction(Company $company)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        // On utilise le SettingsType
        $form = $this->createForm(new CompanyType(), $company);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On enregistre la config
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($company);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Company bien modifié');

                return $this->redirect($this->generateUrl('glstock_company'));
            }
        }

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:edit.html.twig',
            array(
                'form'    => $form->createView(),
                'company' => $company
            )
        );
    }

    /**
     * addFamilyLogAction Ajouter une famille logistique
     * 
     * @return type
     */
    public function addFamilyLogAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $familyLog = new FamilyLog();
        
        $form = $this->createForm(new FamilyLogType(), $familyLog);
        
        // On récupère la requête
        $request = $this->getRequest();

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            $form->bind($request);

            // On vérifie que les valeurs rentrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $article dans la base de données
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($familyLog);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'FamilyLog bien ajoutée');

                // On redirige vers la page de visualisation
                // des configuration de l'appli
                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        // À ce stade :
        // - soit la requête est de type GET,
        // donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - soit la requête est de type POST,
        // mais le formulaire n'est pas valide, donc on l'affiche de nouveau

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * editFamilyLogAction Modifier une famille logistique
     * 
     * @param \Glsr\GestockBundle\Entity\FamilyLog $familyLog
     * Objet famille logistique à modifier
     * 
     * @return type
     */
    public function editFamilyLogAction(FamilyLog $familyLog)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        // On utilise le SettingsType
        $form = $this->createForm(new FamilyLogType(), $familyLog);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On enregistre la config
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($familyLog);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'FamilyLog bien modifié');

                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:edit.html.twig',
            array(
                'form'    => $form->createView(),
                'familylog' => $familyLog
            )
        );
    }

    /**
     * addSubFamilyLogAction Ajouter une sous-famille logistique
     * 
     * @return type
     */
    public function addSubFamilyLogAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $subFamilyLog = new SubFamilyLog();
        
        $form = $this->createForm(new SubFamilyLogType(), $subFamilyLog);
        
        // On récupère la requête
        $request = $this->getRequest();

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            $form->bind($request);

            // On vérifie que les valeurs rentrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $article dans la base de données
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($subFamilyLog);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'SubFamilyLog bien ajoutée');

                // On redirige vers la page de visualisation
                // des configuration de l'appli
                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        // À ce stade :
        // - soit la requête est de type GET,
        // donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - soit la requête est de type POST,
        // mais le formulaire n'est pas valide, donc on l'affiche de nouveau

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * editSubFamilyLogAction Modifier une sous-famille logistique
     * 
     * @param \Glsr\GestockBundle\Entity\SubFamilyLog $subFamilyLog
     * Objet sous-famille logistique à modifier
     * 
     * @return type
     */
    public function editSubFamilyLogAction(SubFamilyLog $subFamilyLog)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        // On utilise le SettingsType
        $form = $this->createForm(new SubFamilyLogType(), $subFamilyLog);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On enregistre la config
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($subFamilyLog);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'SubFamilyLog bien modifié');

                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:edit.html.twig',
            array(
                'form'    => $form->createView(),
                'subfamilylog' => $subFamilyLog
            )
        );
    }

    /**
     * addZoneStorageAction Ajouter une zone de stockage
     * 
     * @return type
     */
    public function addZoneStorageAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $zoneStorage = new ZoneStorage();
        
        $form = $this->createForm(new ZoneStorageType(), $zoneStorage);
        
        // On récupère la requête
        $request = $this->getRequest();

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            $form->bind($request);

            // On vérifie que les valeurs rentrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $article dans la base de données
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($zoneStorage);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'ZoneStorage bien ajoutée');

                // On redirige vers la page de visualisation
                // des configuration de l'appli
                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        // À ce stade :
        // - soit la requête est de type GET,
        // donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - soit la requête est de type POST,
        // mais le formulaire n'est pas valide, donc on l'affiche de nouveau

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * editZoneStorageAction Modifier une zone de stockage
     * 
     * @param \Glsr\GestockBundle\Entity\ZoneStorage $zoneStorage
     * Objet zone de stockage à modifier
     * 
     * @return type
     */
    public function editZoneStorageAction(ZoneStorage $zoneStorage)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        // On utilise le SettingsType
        $form = $this->createForm(new ZoneStorageType(), $zoneStorage);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On enregistre la config
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($zoneStorage);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'ZoneStorage bien modifié');

                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:edit.html.twig',
            array(
                'form'    => $form->createView(),
                'zonestorage' => $zoneStorage
            )
        );
    }
    
    /**
     * addUnitStorageAction Ajouter une unité de stockage
     * 
     * @return type
     */
    public function addUnitStorageAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $unitStorage = new UnitStorage();
        
        $form = $this->createForm(new UnitStorageType(), $unitStorage);
        
        // On récupère la requête
        $request = $this->getRequest();

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            $form->bind($request);

            // On vérifie que les valeurs rentrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $article dans la base de données
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($unitStorage);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'UnitStorage bien ajoutée');

                // On redirige vers la page de visualisation
                // des configuration de l'appli
                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        // À ce stade :
        // - soit la requête est de type GET,
        // donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - soit la requête est de type POST,
        // mais le formulaire n'est pas valide, donc on l'affiche de nouveau

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * editUnitStorageAction Modifier une unité de stockage
     * 
     * @param \Glsr\GestockBundle\Entity\UnitStorage $unitStorage
     * Objet unité de stockage à modifier
     * 
     * @return type
     */
    public function editUnitStorageAction(UnitStorage $unitStorage)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        // On utilise le SettingsType
        $form = $this->createForm(new UnitStorageType(), $unitStorage);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On enregistre la config
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($unitStorage);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'UnitStorage bien modifié');

                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:edit.html.twig',
            array(
                'form'    => $form->createView(),
                'unitstorage' => $unitStorage
            )
        );
    }
    
    /**
     * addTvaAction Ajouter une TVA
     * 
     * @return type
     */
    public function addTvaAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $tva = new Tva();
        
        $form = $this->createForm(new TvaType(), $tva);
        
        // On récupère la requête
        $request = $this->getRequest();

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            $form->bind($request);

            // On vérifie que les valeurs rentrées sont correctes
            if ($form->isValid()) {
                // On enregistre l'objet $article dans la base de données
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($tva);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Tva bien ajoutée');

                // On redirige vers la page de visualisation des
                // configuration de l'appli
                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        // À ce stade :
        // - soit la requête est de type GET,
        // donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - soit la requête est de type POST,
        // mais le formulaire n'est pas valide, donc on l'affiche de nouveau

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * editTvaAction Modifier une TVA
     * 
     * @param \Glsr\GestockBundle\Entity\Tva $tva objet TVA à modifier
     * 
     * @return type
     */
    public function editTvaAction(Tva $tva)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // On définit un message flash
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'Vous devez être connecté pour accéder à cette page.');
            
            // On redirige vers la page de connexion
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        // On utilise le SettingsType
        $form = $this->createForm(new TvaType(), $tva);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On enregistre la config
                $etm = $this->getDoctrine()->getManager();
                $etm->persist($tva);
                $etm->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Tva bien modifié');

                return $this->redirect($this->generateUrl('glstock_divers'));
            }
        }

        return $this->render(
            'GlsrGestockBundle:Gestock/Settings:edit.html.twig',
            array(
                'form'    => $form->createView(),
                'tva' => $tva
            )
        );
    }
}
