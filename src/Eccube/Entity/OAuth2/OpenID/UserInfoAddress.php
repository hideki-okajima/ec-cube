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

namespace Eccube\Entity\OAuth2\OpenID;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserInfoAddress
 *
 * @ORM\Table(name="dtb_oauth2_openid_userinfo_address")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\OAuth2\OpenID\UserInfoAddressRepository")
 */
class UserInfoAddress extends \Eccube\Entity\AbstractEntity
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
     * @ORM\Column(name="formatted", type="string", length=4000, nullable=true)
     */
    private $formatted;

    /**
     * @var string
     *
     * @ORM\Column(name="street_address", type="string", length=4000, nullable=true)
     */
    private $street_address;

    /**
     * @var string
     *
     * @ORM\Column(name="locality", type="string", length=4000, nullable=true)
     */
    private $locality;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=4000, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=255, nullable=true)
     */
    private $postal_code;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

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
     * Set formatted
     *
     * @param string $formatted
     *
     * @return UserInfoAddress
     */
    public function setFormatted($formatted)
    {
        $this->formatted = $formatted;

        return $this;
    }

    /**
     * Get formatted
     *
     * @return string
     */
    public function getFormatted()
    {
        return $this->formatted;
    }

    /**
     * Set street_address
     *
     * @param string $streetAddress
     *
     * @return UserInfoAddress
     */
    public function setStreetAddress($streetAddress)
    {
        $this->street_address = $streetAddress;

        return $this;
    }

    /**
     * Get street_address
     *
     * @return string
     */
    public function getStreetAddress()
    {
        return $this->street_address;
    }

    /**
     * Set locality
     *
     * @param string $locality
     *
     * @return UserInfoAddress
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return UserInfoAddress
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set postal_code
     *
     * @param string $postalCode
     *
     * @return UserInfoAddress
     */
    public function setPostalCode($postalCode)
    {
        $this->postal_code = $postalCode;

        return $this;
    }

    /**
     * Get postal_code
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return UserInfoAddress
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
