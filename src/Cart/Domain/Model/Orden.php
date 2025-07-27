<?php

namespace App\Cart\Domain\Model;

use App\Cart\Domain\ValueObject\OrdenId;
use App\Cart\Domain\ValueObject\Username;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Orden
{   
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'orden_id_vo')]
    private ?OrdenId $id = null;

    #[ORM\Column(type: 'username_vo', length: 100)]
    #[ORM\JoinColumn(name: 'username', referencedColumnName: 'username')]
    private Username $username;

    #[ORM\Column(type: 'boolean')]
    #[ORM\JoinColumn(name: 'pagado', referencedColumnName: 'pagado')]
    private bool $pagado;
    public function __construct(?int $id, Username $username, bool $pagado)
    {
        $this->id = $id;
        $this->username = $username;
        $this->pagado = $pagado;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function setUsername(Username $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPagado(): bool
    {
        return $this->pagado;
    }

    public function setPagado(bool $pagado): static
    {
        $this->pagado = $pagado;

        return $this;
    }
}
