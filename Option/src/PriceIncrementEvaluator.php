<?php

class PriceIncrementEvaluator
{

    private static function evalRules($rule, $domain)
    {
        $logic = $rule['logic'];
        $conditions = $rule['conditions'];
        $result = $rule['result'];

        $outcome = array_reduce($conditions, function ($acc, $condition) use ($domain, $logic) {
            $field = $condition['field'];
            $operator = $condition['operator'];
            $value = $condition['value'];

            $domainValue = $domain[$field];
            $isArrayCheck = is_array($value);

            switch ($operator) {
                case 'exist':
                    $check = !empty($domainValue);
                    break;
                case 'empty':
                    $check = empty($domainValue);
                    break;
                case '=':
                case 'in':
                    $check = $isArrayCheck ? in_array($domainValue, $value) : $domainValue == $value;
                    break;
                case '!=':
                case 'not in':
                    $check = $isArrayCheck ? !in_array($domainValue, $value) : $domainValue != $value;
                    break;
                case 'regex':
                    $check = preg_match($value, $domainValue);
                    break;
                case 'include':
                    $check = is_array($domainValue) && PriceIncrementEvaluator::array_every($domainValue, function ($item) use ($value) {
                        return strpos($item, $value) !== false;
                    });
                    break;
                case 'exclude':
                    $check = is_array($domainValue) && PriceIncrementEvaluator::array_every($domainValue, function ($item) use ($value) {
                        return strpos($item, $value) === false;
                    });
                    break;
                case 'count':
                    $check = is_array($domainValue) && count($domainValue) == $value;
                    break;
                default:
                    $check = false;
            }

            return $logic === 'and' ? $acc && $check : $acc || $check;
        }, true);

        return $outcome ? $result : 0;
    }

    private static function array_every($array, $callback)
    {
        foreach ($array as $item) {
            if (!$callback($item)) {
                return false;
            }
        }
        return true;
    }

    public static function getResult($rules, $domain)
    {
        return array_reduce($rules, function ($acc, $rule) use ($domain) {
            return $acc + PriceIncrementEvaluator::evalRules($rule, $domain);
        }, 0);
    }
}
