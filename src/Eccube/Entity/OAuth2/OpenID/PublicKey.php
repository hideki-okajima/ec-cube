<?php

/*
 * This file is part of the EccubeApi
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Entity\OAuth2\OpenID;

use Doctrine\ORM\Mapping as ORM;

/**
 * PublicKey
 *
 * @ORM\Table(name="dtb_oauth2_openid_public_key")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\OAuth2\OpenID\PublicKeyRepository")
 */
class PublicKey extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="public_key", type="string", length=4000)
     */
    private $public_key;

    /**
     * @var string
     *
     * @ORM\Column(name="private_key", type="string", length=4000)
     */
    private $private_key;

    /**
     * @var string
     *
     * @ORM\Column(name="encryption_algorithm", type="string", length=255, options={"default":"RS256"})
     */
    private $encryption_algorithm;

    /**
     * @var \Eccube\Entity\OAuth2\OpenID\UserInfo
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\OAuth2\OpenID\UserInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userinfo_id", referencedColumnName="id")
     * })
     */
    private $UserInfo;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set public_key
     *
     * @param string $publicKey
     * @return PublicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->public_key = $publicKey;

        return $this;
    }

    /**
     * Get public_key
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }

    /**
     * Set private_key
     *
     * @param string $privateKey
     * @return PublicKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->private_key = $privateKey;

        return $this;
    }

    /**
     * Get private_key
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->private_key;
    }

    /**
     * Set encryption_algorithm
     *
     * @param string $encryptionAlgorithm
     * @return PublicKey
     */
    public function setEncryptionAlgorithm($encryptionAlgorithm)
    {
        $this->encryption_algorithm = $encryptionAlgorithm;

        return $this;
    }

    /**
     * Get encryption_algorithm
     *
     * @return string
     */
    public function getEncryptionAlgorithm()
    {
        return $this->encryption_algorithm;
    }

    /**
     * Set UserInfo
     *
     * @param \Eccube\Entity\OAuth2\OpenID\UserInfo $userInfo
     * @return PublicKey
     */
    public function setUserInfo(\Eccube\Entity\OAuth2\OpenID\UserInfo $userInfo = null)
    {
        $this->UserInfo = $userInfo;

        return $this;
    }

    /**
     * Get UserInfo
     *
     * @return \Eccube\Entity\OAuth2\OpenID\UserInfo
     */
    public function getUserInfo()
    {
        return $this->UserInfo;
    }
}
