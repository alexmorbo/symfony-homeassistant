<?php

namespace AlexMorbo\HomeAssistant\Dto\Supervisor\Network;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Serializer\Annotation\SerializedName;

class NetworkDto
{
    #[ArrayShape(['interfaces' => "array"])]
    public array $interfaces;

    public DockerDto $docker;

    #[SerializedName('host_internet')]
    public bool $hostInternet;

    #[SerializedName('supervisor_internet')]
    public bool $supervisorInternet;
}