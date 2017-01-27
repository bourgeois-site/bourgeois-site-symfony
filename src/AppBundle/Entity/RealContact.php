<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="real_contacts")
 */
class RealContact
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
    private $address;

    /**
     * @ORM\Column(type="string", name="main_phone")
     */
    private $mainPhone;

    /**
     * @ORM\Column(type="string", name="additional_phone", nullable=true)
     */
    private $additionalPhone;

    /**
     * @ORM\Column(type="string", name="work_time")
     */
    private $workTime;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private $longitude;

    /**
     * @ORM\Column(type="boolean", name="is_main_phone", options={"default": false})
     */
    private $isMainPhone;

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
     * @return RealContact
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
     * Set address
     *
     * @param string $address
     *
     * @return RealContact
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set workTime
     *
     * @param string $workTime
     *
     * @return RealContact
     */
    public function setWorkTime($workTime)
    {
        $this->workTime = $workTime;

        return $this;
    }

    /**
     * Get workTime
     *
     * @return string
     */
    public function getWorkTime()
    {
        return $this->workTime;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return RealContact
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return RealContact
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set mainPhone
     *
     * @param string $mainPhone
     *
     * @return RealContact
     */
    public function setMainPhone($mainPhone)
    {
        $this->mainPhone = $mainPhone;

        return $this;
    }

    /**
     * Get mainPhone
     *
     * @return string
     */
    public function getMainPhone()
    {
        return $this->mainPhone;
    }

    /**
     * Set additionalPhone
     *
     * @param string $additionalPhone
     *
     * @return RealContact
     */
    public function setAdditionalPhone($additionalPhone)
    {
        $this->additionalPhone = $additionalPhone;

        return $this;
    }

    /**
     * Get additionalPhone
     *
     * @return string
     */
    public function getAdditionalPhone()
    {
        return $this->additionalPhone;
    }

    /**
     * Set isMainPhone
     *
     * @param boolean $isMainPhone
     *
     * @return RealContact
     */
    public function setIsMainPhone($isMainPhone)
    {
        $this->isMainPhone = $isMainPhone;

        return $this;
    }

    /**
     * Get isMainPhone
     *
     * @return boolean
     */
    public function getIsMainPhone()
    {
        return $this->isMainPhone;
    }
}
