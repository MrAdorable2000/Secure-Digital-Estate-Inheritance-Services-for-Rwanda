<?php
declare(strict_types=1);

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $customMessages = [];

    public function __construct(array $data, array $rules, array $messages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->customMessages = $messages;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(string $field): string
    {
        return $this->errors[$field][0] ?? '';
    }

    private function applyRule(string $field, $value, string $rule): void
    {
        if ($rule === 'required') {
            if ($value === null || $value === '') {
                $this->addError($field, 'required', "The {$field} field is required.");
            }
        } elseif ($rule === 'email') {
            if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->addError($field, 'email', "The {$field} must be a valid email address.");
            }
        } elseif ($rule === 'phone') {
            if ($value && !preg_match('/^\+?[0-9\s\-]{7,20}$/', $value)) {
                $this->addError($field, 'phone', "The {$field} must be a valid phone number.");
            }
        } elseif (str_starts_with($rule, 'min:')) {
            $min = (int) substr($rule, 4);
            if ($value && mb_strlen((string)$value) < $min) {
                $this->addError($field, 'min', "The {$field} must be at least {$min} characters.");
            }
        } elseif (str_starts_with($rule, 'max:')) {
            $max = (int) substr($rule, 4);
            if ($value && mb_strlen((string)$value) > $max) {
                $this->addError($field, 'max', "The {$field} may not be greater than {$max} characters.");
            }
        } elseif ($rule === 'confirmed') {
            $confirmField = $field . '_confirmation';
            if ($value !== ($this->data[$confirmField] ?? null)) {
                $this->addError($field, 'confirmed', "The {$field} confirmation does not match.");
            }
        } elseif ($rule === 'strong_password') {
            if ($value && !$this->isStrongPassword((string)$value)) {
                $this->addError($field, 'strong_password', 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.');
            }
        } elseif (str_starts_with($rule, 'unique:')) {
            $table = substr($rule, 7);
            if ($value && $this->existsInDb($table, $field, $value)) {
                $this->addError($field, 'unique', "The {$field} has already been taken.");
            }
        } elseif (str_starts_with($rule, 'exists:')) {
            $parts = explode(',', substr($rule, 7));
            $table = $parts[0] ?? '';
            $col = $parts[1] ?? $field;
            if ($value && !$this->existsInDb($table, $col, $value)) {
                $this->addError($field, 'exists', "The selected {$field} is invalid.");
            }
        } elseif ($rule === 'nullable') {
            if ($value === null || $value === '') return;
        }
    }

    private function addError(string $field, string $rule, string $defaultMessage): void
    {
        $key = "{$field}.{$rule}";
        $message = $this->customMessages[$key] ?? $defaultMessage;
        $this->errors[$field][] = $message;
    }

    private function isStrongPassword(string $password): bool
    {
        return strlen($password) >= 8
            && preg_match('/[A-Z]/', $password)
            && preg_match('/[a-z]/', $password)
            && preg_match('/[0-9]/', $password)
            && preg_match('/[^A-Za-z0-9]/', $password);
    }

    private function existsInDb(string $table, string $column, $value): bool
    {
        try {
            return (bool) Database::selectScalar(
                "SELECT 1 FROM `{$table}` WHERE `{$column}` = ? AND `deleted_at` IS NULL LIMIT 1",
                [$value]
            );
        } catch (Throwable $e) {
            return false;
        }
    }
}
