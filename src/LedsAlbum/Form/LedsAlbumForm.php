<?php

/**
 * Description of LedsAlbumForm
 *
 * @author kafka
 */

namespace LedsAlbum\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use LedsAlbum\Entity\LedsAlbum;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class LedsAlbumForm extends Form {

    public function __construct() {
        parent::__construct('album');
        $this->setHydrator(new ClassMethodsHydrator(FALSE))
                ->setObject(new LedsAlbum());

        $this->setAttribute('method', 'post');

        $idField = new Element\Hidden('id');
        $this->add($idField);

        $titleField = new Element\Text('title');
        $titleField->setLabel('Title');
        $this->add($titleField);

        $idField = new Element\Select('artist_id');
        $idField->setLabel('Artist');
        $this->add($idField);

        $submitField = new Element\Submit('submit');
        $submitField->setValue('Validation');
        $submitField->setAttribute('id', 'submitbutton');
        $this->add($submitField);
    }

    public function setArtists($artists = array()) {
        $this->get('artist_id')->setValueOptions($artists);
    }

}
