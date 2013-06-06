<?php

namespace Sabre\VObject;

class JCalTest extends \PHPUnit_Framework_TestCase {

    function testToJCal() {

        $cal = new Component\VCalendar();

        $event = $cal->add('VEVENT', array(
            "UID" => "foo",
            "DTSTART" => new \DateTime("2013-05-26 18:10:00Z"),
            "DURATION" => "P1D",
            "CATEGORIES" => array('home', 'testing'),
            "CREATED" => new \DateTime("2013-05-26 18:10:00Z"),
            "ATTACH" => "attachment",
            "ATTENDEE" => "mailto:armin@example.org",
            "GEO" => array(51.96668, 7.61876),
            "SEQUENCE" => 5,
            "FREEBUSY" => array("20130526T210213Z/PT1H", "20130626T120000Z/20130626T130000Z"),
            "URL" => "http://example.org/",
            "TZOFFSETFROM" => "+05:00",
            "RRULE" => array('FREQ' => 'WEEKLY', 'BYDAY' => array('MO','TU')),
        ));

        // Modifying DTSTART to be a date-only.
        $event->dtstart['VALUE'] = 'DATE';
        $event->add("X-BOOL", true, array('VALUE' => 'BOOLEAN'));
        $event->add("X-TIME", "08:00:00", array('VALUE' => 'TIME'));

        $expected = array(
            "vcalendar",
            array(
                array(
                    "version",
                    new \StdClass(),
                    "text",
                    "2.0"
                ),
                array(
                    "prodid",
                    new \StdClass(),
                    "text",
                    "-//Sabre//Sabre VObject " . Version::VERSION . "//EN",
                ),
                array(
                    "calscale",
                    new \StdClass(),
                    "text",
                    "GREGORIAN"
                ),
            ),
            array(
                array("vevent",
                    array(
                        array(
                            "uid", new \StdClass(), "text", "foo",
                        ),
                        array(
                            "dtstart", new \StdClass(), "date", "2013-05-26",
                        ),
                        array(
                            "duration", new \StdClass(), "duration", "P1D",
                        ),
                        array(
                            "categories", new \StdClass(), "text", "home", "testing",
                        ),
                        array(
                            "created", new \StdClass(), "date-time", "2013-05-26T18:10:00Z",
                        ),
                        array(
                            "attach", new \StdClass(), "binary", base64_encode('attachment')
                        ),
                        array(
                            "attendee", new \StdClass(), "cal-address", "mailto:armin@example.org",
                        ),
                        array(
                            "geo", new \StdClass(), "float", 51.96668, 7.61876,
                        ),
                        array(
                            "sequence", new \StdClass(), "integer", 5
                        ),
                        array(
                            "freebusy", new \StdClass(), "period",  "2013-05-26T21:02:13", "PT1H", "2013-06-26T12:00:00", "2013-06-26T13:00:00",
                        ),
                        array(
                            "url", new \StdClass(), "uri", "http://example.org/",
                        ),
                        array(
                            "tzoffsetfrom", new \StdClass(), "utc-offset", "+05:00",
                        ),
                        array(
                            "rrule", new \StdClass(), "recur", array(
                                'freq' => 'WEEKLY',
                                'byday' => array('MO', 'TU'),
                            ),
                        ),
                        array(
                            "x-bool", new \StdClass(), "boolean", true
                        ),
                        array(
                            "x-time", new \StdClass(), "time", "08:00:00",
                        ),
                    ),
                    array(),
                )
            ),
        );

        $this->assertEquals($expected, $cal->jsonSerialize());

    }

}
