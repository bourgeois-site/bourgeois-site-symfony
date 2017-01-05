<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="internet_contacts")
 */
class InternetContact
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $href;

    /**
     * @ORM\Column(type="boolean", name="is_email", options={"default": false})
     */
    private $isEmail;

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
     * Set title
     *
     * @param string $title
     *
     * @return InternetContact
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set href
     *
     * @param string $href
     *
     * @return InternetContact
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Get href
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set isEmail
     *
     * @param boolean $isEmail
     *
     * @return InternetContact
     */
    public function setIsEmail($isEmail)
    {
        $this->isEmail = $isEmail;

        return $this;
    }

    /**
     * Get isEmail
     *
     * @return boolean
     */
    public function getIsEmail()
    {
        return $this->isEmail;
    }
}
