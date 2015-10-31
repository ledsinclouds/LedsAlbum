<?php

namespace LedsAlbum\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\FormInterface;
use LedsAlbum\Form\LedsAlbumForm;
use LedsAlbum\Entity\LedsAlbum;

class LedsAlbumController extends AbstractActionController {

    protected $em;

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction() {
        $resultSet = $this->getEntityManager()->getRepository('LedsAlbum\Entity\LedsAlbum')->findAll();
        return new ViewModel(array(
            'albums' => $resultSet
        ));
    }

    public function addAction() {
        $form = new LedsAlbumForm();
        $form->get('submit')->setAttribute('label', 'Validation');

        $artists = $this->getEntityManager()->getRepository('LedsAlbum\Entity\Artist')->findAll();
        $options = array("" => "");
        foreach ($artists as $artist) {
            $options[$artist->id] = $artist->name;
        }
        $form->setArtists($options);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $album = new LedsAlbum();
            $form->setData($request->getPost());
            $form->setInputFilter($album->getInputFilter());

            if ($form->isValid()) {
                $album->exchangeArray($form->getData(FormInterface::VALUES_AS_ARRAY));

                $artistId = $form->get('artist_id')->getValue();
                $form->bindValues();
                $artist = null;
                if (!empty($artistId)) {
                    $artist = $this->getEntityManager()->find('LedsAlbum\Entity\Artist', $artistId);
                }
                $album->setArtist($artist);
                $this->getEntityManager()->persist($album);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('leds-album');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('leds-album', array('action' => 'add'));
        }

        $album = $this->getEntityManager()->find('LedsAlbum\Entity\LedsAlbum', $id);
        $form = new LedsAlbumForm();

        $artists = $this->getEntityManager()->getRepository('LedsAlbum\Entity\Artist')->findAll();
        $options = array("" => "");
        foreach ($artists as $artist) {
            $options[$artist->id] = $artist->name;
        }
        $form->setArtists($options);

        $form->setBindOnValidate(false);
        $form->bind($album);

        $form->get('artist_id')->setValue($album->getArtist() != null ? $album->getArtist()->getId() : '');
        $form->get('submit')->setAttribute('label', 'Edit');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $artistId = $form->get('artist_id')->getValue();
                $form->bindValues();
                $artist = null;
                if (!empty($artistId)) {
                    $artist = $this->getEntityManager()->find('LedsAlbum\Entity\Artist', $artistId);
                }
                $album->setArtist($artist);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('leds-album');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function deleteAction(){
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id){
            return $this->redirect()->toRoute('leds-album');
        }
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $del = $request->getPost('del', 'Not');
            if ($del == 'Yes'){
                $id = (int) $request->getPost('id');
                $album = $this->getEntityManager()->find('LedsAlbum\Entity\LedsAlbum', $id);
                if ($album){
                    $this->getEntityManager()->remove($album);
                    $this->getEntityManager()->flush();
                }
            }
            return $this->redirect()->toRoute('leds-album');
        }
        return array(
            'id' => $id,
            'album' => $this->getEntityManager()->find('LedsAlbum\Entity\LedsAlbum', $id)
        );
    }

}
