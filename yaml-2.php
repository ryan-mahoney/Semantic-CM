<?php
namespace Manager;

//tmp
$managers = 'Blogs
Blurbs
Books
Carousels
Categories
DepartmentProfiles
Departments
EventsDiscounts
EventsEmails
EventsExceptions
EventsHighlights
EventsImages
EventsLinks
EventsPeoples
Events
EventsPlus
EventsRecurrences
EventsRegistrations
EventsSponsors
FileUploads
Jobs
Languages
Links
MembershipLevels
MenuLinks
Menus
Pages
PhotoGalleries
Podcasts
PracticeAreas
Profiles
Programs
Publications
Resources
SocialLinks
Sponsors
Subcarousels
Subcategories
Subimages
SystemMessages
Testimonials
UserGroups
UsersAddress
Videos';

$managers = explode("\n", $managers);

foreach ($managers as $manager) {
    require_once __DIR__.'/available/'.$manager.'.php';
    $class = 'Manager\\'.$manager;
    $obj = new $class();
    $reflect = new \ReflectionClass($obj);
    $properties = $reflect->getProperties();
    $methods = $reflect->getMethods();
    format($manager, $class, $obj, $properties, $methods);
}

function format($manager, $class, $obj, $properties, $methods)
{
    ob_start();
    echo 'manager:', "\n";
    echo '    slug: ', $manager, "\n";
    foreach ($properties as $prop) {
        $propName = $prop->getName();
        if ($propName == 'description') {
            continue;
        }
        if ($propName == 'sort') {
            echo '    sort: \'', $prop->getValue($obj), "'\n";
            continue;
        }
        echo '    ', $propName, ': ';
        $value = $prop->getValue($obj);
        switch (gettype($value)) {
            case 'string':
                echo $value, "\n";
                break;

            case 'array':
                echo arrayFormat($value), "\n";
                break;

            case 'boolean':
                echo $value === true ? 'true' : 'false', "\n";
                break;

            case 'NULL':
                echo "\n";
                break;

            default:
                exit('unknown type: '.gettype($value).', for: '.$propName);
        }
    }
    echo "\n", '    fields:', "\n";
    foreach ($methods as $method) {
        $name = $method->getName();
        if (substr_count($method->getName(), 'Field') < 1) {
            continue;
        }
        $field = $method->invoke($obj);
        echo '        ', $field['name'], ":\n";
        foreach ($field as $key => $value) {
            if ($key == 'name') {
                continue;
            }
            if ($key == 'display') {
                echo '            display: ', str_replace('Field\\', 'field', $value), '@render', "\n";
                continue;
            }
            if ($key == 'options' && gettype($value) == 'array') {
                echo '            options:', "\n", '                type: array', "\n", '                value: ', arrayFormat($value), "\n";
                continue;
            }
            echo '            ', $key, ': ';
            switch (gettype($value)) {
                case 'string':
                    echo $value, "\n";
                    break;

                case 'array':
                    echo arrayFormat($value), "\n";
                    break;

                case 'boolean':
                    echo $value === true ? 'true' : 'false', "\n";
                    break;

                case 'object':
                    echo 'XXX ', $field['name'], ' ', $key, "\n";
                    break;

                case 'NULL':
                    echo "\n";
                    break;

                default:
                    exit('unknown type: '.gettype($value).', for: '.$key);
            }
        }
    }

    echo "\n", '    indexPartial: |', "\n";
    $reflectionMethod = new \ReflectionMethod($class, 'indexPartial');
    $partial = explode("\n", preg_replace("/[\n]+/", "\n", rtrim($reflectionMethod->invoke($obj))));
    foreach ($partial as $line) {
        if (rtrim($line) == '') {
            continue;
        }
        echo substr(rtrim($line), 4), "\n";
    }

    echo "\n", '    formPartial: |', "\n";
    $reflectionMethod = new \ReflectionMethod($class, 'formPartial');
    $partial = explode("\n", preg_replace("/[\n]+/", "\n", rtrim($reflectionMethod->invoke($obj))));
    foreach ($partial as $line) {
        if (rtrim($line) == '') {
            continue;
        }
        echo substr(rtrim($line), 4), "\n";
    }
    $data = ob_get_clean();
    file_put_contents(__DIR__.'/available/'.strtolower($manager).'.yml', $data);
}

function arrayFormat(&$array)
{
    return '['.implode(', ', $array).']';
}
