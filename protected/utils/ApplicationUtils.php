<?php

namespace org\csflu\isms\util;

use org\csflu\isms\exceptions\ApplicationException;

/**
 * 
 * @author britech
 */
class ApplicationUtils {

    private function __construct() {
        
    }

    public static function generateLink($url, $linkName, array $linkOptions = []) {
        if (is_array($url)) {
            $formedUrl = self::resolveUrl($url);
        } else {
            $formedUrl = $url;
        }
        $otherProperties = self::resolveProperties($linkOptions);
        return "<a href=\"{$formedUrl}\" {$otherProperties}>{$linkName}</a>";
    }

    private static function resolveProperties(array $properties) {
        $class = self::getProperty($properties, 'class');
        $id = self::getProperty($properties, 'id');
        $style = self::getProperty($properties, 'style');
        $target = self::getProperty($properties, 'target');

        $propertiesTag = "id=\"{$id}\" class=\"{$class}\" style=\"${style}\" target=\"{$target}\"";

        return $propertiesTag;
    }

    public static function resolveUrl($url) {
        if (count($url) > 1) {
            $params = "";
            foreach ($url as $paramName => $paramValue) {
                if (!is_numeric($paramName)) {
                    $params.='&' . $paramName . '=' . $paramValue;
                }
            }
            return '?r=' . $url[0] . $params;
        } else {
            return '?r=' . $url[0];
        }
    }

    public static function getProperty(array $property, $propertyName) {
        return array_key_exists($propertyName, $property) ? $property[$propertyName] : NULL;
    }

    public static function generateListData(array $models, $valueMember, $displayMember) {

        $listValues = array("");
        $listNames = array("");

        foreach ($models as $model) {
            if (property_exists($model, $valueMember)) {
                array_push($listValues, $model->$valueMember);
            } elseif (method_exists($model, $valueMember)) {
                array_push($listValues, call_user_func([$model, $valueMember]));
            } else {
                throw new ApplicationException("List Data Generation failure. Defined member does not exist");
            }

            if (property_exists($model, $displayMember)) {
                array_push($listNames, $model->$displayMember);
            } elseif (method_exists($model, $displayMember)) {
                array_push($listNames, call_user_func([$model, $displayMember]));
            } else {
                throw new ApplicationException("List Data Generation failure. Defined member does not exist");
            }
        }
        return array_combine($listValues, $listNames);
    }

    /**
     * 
     * @param \DateTime $period
     * @return \DateTime
     */
    public static function generateEndingPeriodDate(\DateTime $period) {
        $year = intval($period->format('Y'));
        $month = intval($period->format('n'));
        $day = 1;

        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                $day = "31";
                break;
            
            case 4:
            case 6:
            case 7:
            case 11:
                $day = "30";
                break;
            
            case 2:
                $day = boolval($period->format('L')) ? "29" : "28";
                break;
        }

        return \DateTime::createFromFormat('Y-n-d', "{$year}-{$month}-{$day}");
    }

}
