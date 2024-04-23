<?php

namespace Gaiproject\Option;

class PriceIncrementEvaluator
{
    private static function evalRules($rule, $domain)
    {
        $logic = $rule['logic'];
        $conditions = collect($rule['conditions']);
        $result = $rule['result'];

        $outcome = $conditions->map(function (array $condition) use ($domain, $logic) {
            $field = $condition['field'];
            $operator = $condition['operator'];
            $value = $condition['value'];

            $domainValue = $domain[$field];
            $isArrayCheck = is_array($value);
            $check = null;

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
                    $check = is_array($domainValue) && collect($domainValue)->every(fn ($item) => in_array($item, $value));
                    break;
                case 'exclude':
                    $check = is_array($domainValue) && collect($domainValue)->every(fn ($item) => !in_array($item, $value));
                    break;
                case 'count':
                    $check = is_array($domainValue) && count($domainValue) == $value;
                    break;
                default:
                    $check = false;
            }
            return $check;
        });

        if($logic == 'and') {
            return collect($outcome)->every(fn ($val) => $val == true) ? floatval($result) : floatval(0);
        }
        return collect($outcome)->some(fn ($val) => $val == true) ? floatval($result) : floatval(0);
    }


    public static function getResult($rules, $domain)
    {
        return array_reduce($rules, function ($acc, $rule) use ($domain) {
            return $acc += PriceIncrementEvaluator::evalRules($rule, $domain);
        }, 0);
    }
}
