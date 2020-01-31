<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Form\Type\OAuth2;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\OAuth2\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApiClientType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * ApiClientType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('app_name', TextType::class, [
                'label' => 'アプリケーション名',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('redirect_uri', TextType::class, [
                'label' => 'redirect_uri',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 2000]),
                ],
            ])
            ->add('client_identifier', TextType::class, [
                'label' => 'Client ID',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 80,
                    ]),
                ],
            ])
            ->add('client_secret', TextType::class, [
                'label' => 'Client secret',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 80,
                    ]),
                ],
            ])
            ->add('Scopes', EntityType::class, [
                'label' => 'scope',
                'choice_label' => 'label',
                'choice_value' => 'scope',
                'choice_name' => 'scope',
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'required' => false,
                'class' => 'Eccube\Entity\OAuth2\Scope',
            ])
            ->add('public_key', TextareaType::class, [
                'label' => 'id_token 公開鍵',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 2000,
                    ]),
                ],
            ])
            ->add('encryption_algorithm', TextType::class, [
                'label' => 'id_token 暗号化アルゴリズム',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 100,
                    ]),
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'api_client';
    }
}
