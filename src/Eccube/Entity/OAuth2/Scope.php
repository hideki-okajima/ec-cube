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

namespace Eccube\Entity\OAuth2;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scope
 *
 * @ORM\Table(
 *     name="dtb_oauth2_scope",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="scope",columns={"scope"})},
 *     indexes={@ORM\Index(name="dtb_oauth2_scope_index", columns={"scope"})}
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\OAuth2\ScopeRepository")
 */
class Scope extends \Eccube\Entity\AbstractEntity
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
     * @ORM\Column(name="scope", type="string", length=255)
     */
    private $scope;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean", options={"default":false})
     */
    private $is_default;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Eccube\Entity\OAuth2\ClientScope", mappedBy="Scope", cascade={"persist","remove"})
     */
    private $ClientScope;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ClientScope = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set scope
     *
     * @param string $scope
     *
     * @return Scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Scope
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set is_default
     *
     * @param boolean $isDefault
     *
     * @return Scope
     */
    public function setDefault($isDefault)
    {
        $this->is_default = $isDefault;

        return $this;
    }

    /**
     * Get is_default
     *
     * @return boolean
     */
    public function getDefault()
    {
        return $this->is_default;
    }

    public function isDefault()
    {
        return $this->is_default;
    }

    /**
     * Add ClientScope
     *
     * @param \Eccube\Entity\OAuth2\ClientScope $clientScope
     *
     * @return Scope
     */
    public function addClientScope(\Eccube\Entity\OAuth2\ClientScope $clientScope)
    {
        $this->ClientScope[] = $clientScope;

        return $this;
    }

    /**
     * Remove ClientScope
     *
     * @param \Eccube\Entity\OAuth2\ClientScope $clientScope
     */
    public function removeClientScope(\Eccube\Entity\OAuth2\ClientScope $clientScope)
    {
        $this->ClientScope->removeElement($clientScope);
    }

    /**
     * Get ClientScope
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientScope()
    {
        return $this->ClientScope;
    }
}
