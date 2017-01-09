<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('timediff', array($this, 'timeDiffFilter')),
        );
    }

    public function timeDiffFilter($oldTime)
    {
        $currentTime = new \DateTime('NOW');
        $diff = $oldTime->diff($currentTime);
        $diffYear = $diff->y;
        $diffMonth = $diff->m;
        $diffDays = $diff->days;
        $diffHours = $diff->h;
        $diffMinutes = $diff->i;
        $diffSeconds = $diff->s;
        if ($diffYear !== 0) {
            $diff = 'More than year ago';
        } elseif ($diffMonth !== 0) {
            $diff = $diffMonth.' months ago';
        } elseif ($diffDays !== 0) {
            $diff = $diffDays.' days ago';
        } elseif ($diffHours !== 0) {
            $diff = $diffHours.' hours ago';
        } elseif ($diffMinutes !== 0) {
            $diff = $diffMinutes.' minutes ago';
        } else {
            $diff = $diffSeconds.' seconds ago';
        }

        return $diff;
    }

    public function getName()
    {
        return 'app_extension';
    }
}
