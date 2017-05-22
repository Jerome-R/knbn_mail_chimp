<?php

namespace Application\Sonata\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class UserAdmin extends SonataUserAdmin
{
    /**
        * {@inheritdoc}
        */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('General')
                ->add('role', 'choice', array(
                        'choices' => array(
                            'ROLE_BOUTIQUE'         => 'ROLE_BOUTIQUE',
                            'ROLE_DIRECTEUR'        => 'ROLE_DIRECTEUR',
                            'ROLE_RETAIL_MANAGER'   => 'ROLE_RETAIL_MANAGER',
                            'ROLE_SIEGE'            => 'ROLE_SIEGE',
                            ),
                        'required' => false,
                        'label' => 'Role'
                        )
                    )
                ->add('brand', 'text', array(
                       'label' => 'Marque',
                       'required' => false,
                    )
                ) 
                ->add('libelle', 'text', array(
                       'label' => 'Libellé',
                       'required' => false,
                    )
                ) 
                ->add('signature', 'text', array(
                       'label' => 'Signature',
                       'required' => false,
                    )
                )  
                ->add('emailReply', 'text', array(
                       'label' => 'Email de Reply (à remplir uniquement si c\'est une boutique temporaire avec le même email que la boutique non temporaire)',
                       'required' => false,
                    )
                ) 
                ->add('store', 'text', array(
                       'label' => 'Boutique',
                       'required' => false,
                    )
                ) 
                ->add('directeur', 'text', array(
                       'label' => 'Directeur',
                       'required' => false,
                    )
                ) 
                ->add('retailManager', 'text', array(
                       'label' => 'Retail Manager',
                       'required' => false,
                    )
                ) 
                ->add('authenticationFailure', 'integer', array(
                       'label' => 'authenticationFailure',
                       'required' => false,
                    )
                )                
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            ->add('directeur', null, array(
                'label' => 'Directeur'
                )
            )
            ->add('retailManager', null, array(
                'label' => 'Retail Manager'
                )
            )
            ->add('role', null, array(
                'label' => 'Role'
                )
            )
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        //parent::configureListFields($listMapper);

        $listMapper
            ->addIdentifier('id', 'integer', array(
                'label' => 'Id'
                )
            )
            ->addIdentifier('libelle', 'text', array(
                'label' => 'Libellé'
                )
            )
            ->add('signature', 'text', array(
                'label' => 'Signature'
                )
            )
            ->add('role', null, array(
                'label' => 'Role'
                )
            )
            ->add('email', 'text', array(
                'label' => 'Email'
                )
            )
            ->add('Directeur', 'text', array(
                'label' => 'Directeur'
                )
            )
            ->add('retailManager', 'text', array(
                'label' => 'Retail Manager'
                )
            )

            //->remove('groups')
            //->remove('createdAt')
            //->remove('brand')
            //->remove('impersonating')
            //->remove('enabled')
            //->remove('roles')
            //->add('userModules', 'associated_property')
        ;
    }
}