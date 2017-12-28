<?php
namespace <%PKGCLASS%>\Entity;

use Doctrine\ORM\Mapping as ORM;

use Wasm\StoreBundle\EntityField;

/**
 * @ORM\Table(
 *     name="<%TABLE%>"
 * )
 * @ORM\Entity(
 *     repositoryClass="<%PKGCLASS%>\Store\<%MODNAME%>Repo"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class <%MODPREFIX%>Slide
{
    use EntityField\IdField;
    use EntityField\NameField;
    use EntityField\DescriptionField;
    use EntityField\SortField;
    use EntityField\IsEnabledField;
    use EntityField\CreatedAtField;
}