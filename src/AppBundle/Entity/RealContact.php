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
     * @ORM\Column(type="string")
     */
    private $phone_numbers;

    /**
     * @ORM\Column(type="string")
     */
    private $work_time;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private $longitude;

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
     * Set phoneNumbers
     *
     * @param string $phoneNumbers
     *
     * @return RealContact
     */
    public function setPhoneNumbers($phoneNumbers)
    {
        $this->phone_numbers = $phoneNumbers;

        return $this;
    }

    /**
     * Get phoneNumbers
     *
     * @return string
     */
    public function getPhoneNumbers()
    {
        return $this->phone_numbers;
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
        $this->work_time = $workTime;

        return $this;
    }

    /**
     * Get workTime
     *
     * @return string
     */
    public function getWorkTime()
    {
        return $this->work_time;
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
}
