<?php

namespace LedsAlbum\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\FormInterface;
use LedsAlbum\Form\ArtistForm;
use LedsAlbum\Entity\Artist;

class ArtistController extends AbstractActionController {

    protected $em;

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction() {
        $resultSet = $this->getEntityManager()->getRepository('LedsAlbum\Entity\Artist')->findAll();
        return new ViewModel(array(
            'artists' => $resultSet
        ));
    }

    public function addAction() {
        $form = new ArtistForm();
        $form->get('submit')->setAttribute('label', 'Validation');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $artist = new Artist();
            $form->setData($request->getPost());
            $form->setInputFilter($artist->getInputFilter());
            if ($form->isValid()) {
                $artist->exchangeArray($form->getData(FormInterface::VALUES_AS_ARRAY));
                $form->bindValues();
                $this->getEntityManager()->persist($artist);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Artist added');
                return $this->redirect()->toRoute('artist');
            }
        }
        return array('form' => $form);
    }

    public function deleteAction(){
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id){
            return $this->redirect()->toRoute('artist');
        }
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $del = $request->getPost('del', 'Not');
            if ($del == 'Yes'){
                $id = (int) $request->getPost('id');
                $artist = $this->getEntityManager()->find('LedsAlbum\Entity\Artist', $id);
                if ($artist){
                    $this->getEntityManager()->remove($artist);
                    $this->getEntityManager()->flush();
                }
            }
            return $this->redirect()->toRoute('artist');
        }
        return array(
            'id' => $id,
            'artist' => $this->getEntityManager()->find('LedsAlbum\Entity\Artist', $id)
        );
    }

//    public function deleteAction() {
//        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
//        if (!$id) {
//            return $this->redirect()->toRoute('artist');
//        }
//
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $del = $request->getPost('del', 'Not');
//            if ($del == 'Yes') {
//                $id = (int) $request->getPost('id');
//                $artist = $this->getEntityManager()->find('LedsAlbum\Entity\Artist', $id);
//
//                $album = $this->getEntityManager()->getRepository('LedsAlbum\Entity\LedsAlbum')->findBy(array('id'));
//                $criteria = array('artist_id' => $album->getId());
//                $albumId = $this->getEntityManager()->getRepository('LedsAlbum\Entity\LedsAlbum', $criteria);
//
//                if ($artist) {
//                    if (count($albumId) != 0) {
//                        $this->redirect()->toRoute('album');
//                    } else {
//                        $this->getEntityManager()->remove($artist);
//                        $this->getEntityManager()->flush();
//                    }
//                }
//            }
//            return $this->redirect()->toRoute('artist');
//        }
//        return array(
//            'id' => $id,
//            'artist' => $this->getEntityManager()->find('LedsAlbum\Entity\Artist', $id)
//        );
//    }

}
