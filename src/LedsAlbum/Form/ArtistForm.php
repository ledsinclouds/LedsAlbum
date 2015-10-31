<?php

/**
 * Description of ArtistForm
 *
 * @author kafka
 */

namespace LedsAlbum\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use LedsAlbum\Entity\Artist;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodHydrator;

class ArtistForm extends Form {

    public function __construct() {
        parent::__construct('artist');
        $this->setHydrator(new ClassMethodHydrator(FALSE))
                ->setObject(new Artist());
        $this->setAttribute('method', 'post');

        $idField = new Element\Hidden('id');
        $this->add($idField);

        $nameField = new Element\Text('name');
        $nameField->setLabel('Artist');
        $this->add($nameField);

        $submitField = new Element\Submit('submit');
        $submitField->setValue('Validation');
        $submitField->setAttribute('id', 'submibutton');
        $this->add($submitField);
    }

}
