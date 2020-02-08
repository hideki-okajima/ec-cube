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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Trikoder\Bundle\OAuth2Bundle\OAuth2Grants;

class ClientType extends AbstractType
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
            ->add('redirect_uri', TextType::class, [
                'label' => 'redirect-uri',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_url_len']]),
                ],
            ])
            ->add('grant_type', ChoiceType::class, [
                'label' => 'grant-type',
                'choices' => [
                    OAuth2Grants::AUTHORIZATION_CODE => OAuth2Grants::AUTHORIZATION_CODE,
                    OAuth2Grants::CLIENT_CREDENTIALS => OAuth2Grants::CLIENT_CREDENTIALS,
                    OAuth2Grants::IMPLICIT => OAuth2Grants::IMPLICIT,
                    OAuth2Grants::PASSWORD => OAuth2Grants::PASSWORD,
                    OAuth2Grants::REFRESH_TOKEN => OAuth2Grants::REFRESH_TOKEN,
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('scope', ChoiceType::class, [
                'label' => 'scope',
                'choices' => [
                    'read' => 'read', // TODO 定数化
                    'write' => 'write', // TODO 定数化
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('identifier', TextType::class, [
                'label' => 'identifier',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_id_max_len']]),
                ],
            ])
            ->add('secret', TextType::class, [
                'label' => 'secret',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_smtext_len']]),
                ],
            ])
            ->add('activated', ChoiceType::class, [
                'label' => 'activated',
                'choices' => [
                    'activated' => true,
                    'deactivated' => false,
                ],
                'required' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oauth2_client';
    }
}
