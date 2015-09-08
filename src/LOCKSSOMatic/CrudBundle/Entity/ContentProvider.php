<?php

namespace LOCKSSOMatic\CrudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentProvider
 *
 * @ORM\Table(name="content_providers", indexes={@ORM\Index(name="IDX_D2C29C14138983FF", columns={"content_owner_id"}), @ORM\Index(name="IDX_D2C29C14C8BA1A08", columns={"pln_id"})})
 * @ORM\Entity
 */
class ContentProvider
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=24, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=36, nullable=false)
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="permissionUrl", type="string", length=255, nullable=false)
     */
    private $permissionurl;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=16, nullable=false)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=255, nullable=false)
     */
    private $hostname;

    /**
     * @var string
     *
     * @ORM\Column(name="checksum_type", type="string", length=24, nullable=false)
     */
    private $checksumType;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_file_size", type="integer", nullable=true)
     */
    private $maxFileSize;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_au_size", type="integer", nullable=true)
     */
    private $maxAuSize;

    /**
     * @var ContentOwner
     *
     * @ORM\ManyToOne(targetEntity="ContentOwner")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="content_owner_id", referencedColumnName="id")
     * })
     */
    private $contentOwner;

    /**
     * @var Pln
     *
     * @ORM\ManyToOne(targetEntity="Pln")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pln_id", referencedColumnName="id")
     * })
     */
    private $pln;



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
     * Set type
     *
     * @param string $type
     * @return ContentProvider
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     * @return ContentProvider
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string 
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set permissionurl
     *
     * @param string $permissionurl
     * @return ContentProvider
     */
    public function setPermissionurl($permissionurl)
    {
        $this->permissionurl = $permissionurl;

        return $this;
    }

    /**
     * Get permissionurl
     *
     * @return string 
     */
    public function getPermissionurl()
    {
        return $this->permissionurl;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ContentProvider
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return ContentProvider
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return ContentProvider
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Get hostname
     *
     * @return string 
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set checksumType
     *
     * @param string $checksumType
     * @return ContentProvider
     */
    public function setChecksumType($checksumType)
    {
        $this->checksumType = $checksumType;

        return $this;
    }

    /**
     * Get checksumType
     *
     * @return string 
     */
    public function getChecksumType()
    {
        return $this->checksumType;
    }

    /**
     * Set maxFileSize
     *
     * @param integer $maxFileSize
     * @return ContentProvider
     */
    public function setMaxFileSize($maxFileSize)
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    /**
     * Get maxFileSize
     *
     * @return integer 
     */
    public function getMaxFileSize()
    {
        return $this->maxFileSize;
    }

    /**
     * Set maxAuSize
     *
     * @param integer $maxAuSize
     * @return ContentProvider
     */
    public function setMaxAuSize($maxAuSize)
    {
        $this->maxAuSize = $maxAuSize;

        return $this;
    }

    /**
     * Get maxAuSize
     *
     * @return integer 
     */
    public function getMaxAuSize()
    {
        return $this->maxAuSize;
    }

    /**
     * Set contentOwner
     *
     * @param ContentOwner $contentOwner
     * @return ContentProvider
     */
    public function setContentOwner(ContentOwner $contentOwner = null)
    {
        $this->contentOwner = $contentOwner;

        return $this;
    }

    /**
     * Get contentOwner
     *
     * @return ContentOwner 
     */
    public function getContentOwner()
    {
        return $this->contentOwner;
    }

    /**
     * Set pln
     *
     * @param Pln $pln
     * @return ContentProvider
     */
    public function setPln(Pln $pln = null)
    {
        $this->pln = $pln;

        return $this;
    }

    /**
     * Get pln
     *
     * @return Pln 
     */
    public function getPln()
    {
        return $this->pln;
    }
}