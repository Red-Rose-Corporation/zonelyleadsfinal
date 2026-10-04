<?php

namespace App\Support;

use App\Models\Certification;
use App\Models\Contact;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Single source of truth for the simple, repeatable profile sections an admin can
 * manage on a seller's behalf (experience, education, certifications, memberships,
 * languages, contacts). The fields and validation rules mirror the seller's own
 * dashboard controllers, and drive both the admin form and the server-side checks.
 */
class ProfileSectionDefs
{
    public static function all(): array
    {
        return [
            'experiences' => [
                'model'    => Experience::class,
                'title'    => 'Experience',
                'item'     => 'experience',
                'icon'     => 'fa-briefcase',
                'header'   => 'bg-info text-dark',
                'hint'     => 'Work history shown on the public profile.',
                'order'    => fn ($q) => $q->orderByDesc('is_current')->orderByDesc('start_date')->orderBy('id'),
                'fields'   => [
                    ['name' => 'title',       'label' => 'Job title',            'type' => 'text',     'required' => true, 'max' => 255, 'col' => 6, 'placeholder' => 'e.g. Senior Manager, CPA, Tax Advisor'],
                    ['name' => 'company',     'label' => 'Company',              'type' => 'text',     'max' => 255,  'col' => 6, 'placeholder' => 'e.g. Ernst & Young, Self-employed'],
                    ['name' => 'start_date',  'label' => 'Start',                'type' => 'text',     'max' => 20,   'col' => 4, 'placeholder' => 'e.g. Jan 2018'],
                    ['name' => 'end_date',    'label' => 'End',                  'type' => 'text',     'max' => 20,   'col' => 4, 'placeholder' => 'e.g. Dec 2022'],
                    ['name' => 'is_current',  'label' => 'Currently works here', 'type' => 'checkbox', 'col' => 4],
                    ['name' => 'description', 'label' => 'Description',          'type' => 'textarea', 'max' => 1000, 'col' => 12, 'rows' => 3],
                ],
                'summary'  => fn ($r) => [
                    $r->title,
                    trim(($r->company ? $r->company . ' · ' : '') . ($r->start_date ?: '') . ' – ' . ($r->is_current ? 'Present' : ($r->end_date ?: '')), ' –·'),
                ],
            ],

            'educations' => [
                'model'   => Education::class,
                'title'   => 'Education',
                'item'    => 'education entry',
                'icon'    => 'fa-graduation-cap',
                'header'  => 'bg-success text-white',
                'hint'    => 'Degrees and schools shown on the public profile.',
                'order'   => fn ($q) => $q->orderBy('id'),
                'fields'  => [
                    ['name' => 'degree',       'label' => 'Degree / qualification', 'type' => 'text', 'required' => true, 'max' => 255, 'col' => 6, 'placeholder' => 'e.g. J.D., B.Sc. Accounting'],
                    ['name' => 'institution',  'label' => 'School / institution',   'type' => 'text', 'max' => 255, 'col' => 6],
                    ['name' => 'passing_year', 'label' => 'Year',                   'type' => 'text', 'max' => 20,  'col' => 4, 'placeholder' => 'e.g. 2015'],
                ],
                'summary' => fn ($r) => [$r->degree, trim(($r->institution ?: '') . ($r->passing_year ? ' · ' . $r->passing_year : ''), ' ·')],
            ],

            'certifications' => [
                'model'   => Certification::class,
                'title'   => 'Certifications & Licenses',
                'item'    => 'certification',
                'icon'    => 'fa-certificate',
                'header'  => 'bg-warning text-dark',
                'hint'    => 'Only enter licenses and certificates the professional actually holds.',
                'order'   => fn ($q) => $q->orderBy('id'),
                'fields'  => [
                    ['name' => 'name',          'label' => 'Certificate / license', 'type' => 'text', 'required' => true, 'max' => 255, 'col' => 6],
                    ['name' => 'issuer',        'label' => 'Issued by',             'type' => 'text', 'max' => 255, 'col' => 6],
                    ['name' => 'issued_year',   'label' => 'Issued (year)',         'type' => 'text', 'max' => 20,  'col' => 4],
                    ['name' => 'expiry_year',   'label' => 'Expires (year)',        'type' => 'text', 'max' => 20,  'col' => 4],
                    ['name' => 'credential_id', 'label' => 'Credential / ID',      'type' => 'text', 'max' => 255, 'col' => 4],
                ],
                'summary' => fn ($r) => [$r->name, trim(($r->issuer ?: '') . ($r->issued_year ? ' · ' . $r->issued_year : ''), ' ·')],
            ],

            'memberships' => [
                'model'   => Membership::class,
                'title'   => 'Memberships',
                'item'    => 'membership',
                'icon'    => 'fa-id-badge',
                'header'  => 'bg-primary text-white',
                'hint'    => 'Professional associations and bar memberships.',
                'order'   => fn ($q) => $q->orderBy('id'),
                'fields'  => [
                    ['name' => 'name',    'label' => 'Organization', 'type' => 'text', 'required' => true, 'max' => 255, 'col' => 6],
                    ['name' => 'address', 'label' => 'Location',     'type' => 'text', 'max' => 255, 'col' => 6],
                    ['name' => 'start',   'label' => 'Since',        'type' => 'text', 'max' => 20,  'col' => 3, 'placeholder' => 'e.g. 2012'],
                    ['name' => 'end',     'label' => 'Until',        'type' => 'text', 'max' => 20,  'col' => 3],
                ],
                'summary' => fn ($r) => [$r->name, trim(($r->address ?: '') . ($r->start ? ' · since ' . $r->start : ''), ' ·')],
            ],

            'languages' => [
                'model'   => Language::class,
                'title'   => 'Languages',
                'item'    => 'language',
                'icon'    => 'fa-language',
                'header'  => 'bg-secondary text-white',
                'hint'    => 'Languages the professional speaks.',
                'order'   => fn ($q) => $q->orderBy('id'),
                'fields'  => [
                    ['name' => 'name', 'label' => 'Language', 'type' => 'text', 'required' => true, 'max' => 255, 'col' => 6, 'placeholder' => 'e.g. English, Spanish'],
                ],
                'summary' => fn ($r) => [$r->name, ''],
            ],

            'contacts' => [
                'model'   => Contact::class,
                'title'   => 'Contact Details',
                'item'    => 'contact',
                'icon'    => 'fa-address-book',
                'header'  => 'bg-dark text-white',
                'hint'    => 'The first WhatsApp entry feeds the WhatsApp button on the public profile. Phone, email and address are stored on the account but are not shown on the public page right now.',
                'order'   => fn ($q) => $q->orderBy('id'),
                'fields'  => [
                    ['name' => 'type',  'label' => 'Type',  'type' => 'select', 'required' => true, 'col' => 4,
                     'options' => ['email' => 'Email', 'phone' => 'Phone', 'whatsapp' => 'WhatsApp', 'address' => 'Address']],
                    ['name' => 'value', 'label' => 'Value', 'type' => 'text', 'required' => true, 'max' => 500, 'col' => 8, 'placeholder' => 'e.g. +1 212 555 0100'],
                ],
                // Same per-type checks as the seller's own Contacts form.
                'override' => fn (Request $r) => ['value' => match ($r->input('type')) {
                    'email'             => ['required', 'email', 'max:255'],
                    'phone', 'whatsapp' => ['required', 'string', 'regex:/^\+?[0-9\s\-\(\)]{7,20}$/'],
                    default             => ['required', 'string', 'max:500'],
                }],
                'summary' => fn ($r) => [ucfirst($r->type), (string) $r->value],
            ],
        ];
    }

    /** Same timezone choices as the seller's own Working Hours form. */
    public static function timezones(): array
    {
        return [
            'America/New_York'    => 'Eastern Time (ET)',
            'America/Chicago'     => 'Central Time (CT)',
            'America/Denver'      => 'Mountain Time (MT)',
            'America/Phoenix'     => 'Mountain Time – Arizona',
            'America/Los_Angeles' => 'Pacific Time (PT)',
            'America/Anchorage'   => 'Alaska Time',
            'Pacific/Honolulu'    => 'Hawaii Time',
            'America/Puerto_Rico' => 'Atlantic Time',
            'Europe/London'       => 'London (GMT/BST)',
            'Europe/Paris'        => 'Central European Time',
            'Asia/Dubai'          => 'Dubai (GST)',
            'Asia/Karachi'        => 'Pakistan (PKT)',
            'Asia/Dhaka'          => 'Bangladesh (BST)',
            'Asia/Kolkata'        => 'India (IST)',
            'Australia/Sydney'    => 'Sydney (AEST)',
        ];
    }

    public static function responseTimes(): array
    {
        return [
            '30_min'   => 'Within 30 minutes',
            '1_hour'   => 'Within 1 hour',
            '4_hours'  => 'Within 4 hours',
            '24_hours' => 'Within 24 hours',
            '48_hours' => 'Within 2 days',
        ];
    }

    /** Validation rules built from the field list (plus any per-section override). */
    public static function rules(array $def, Request $request): array
    {
        $rules = [];
        foreach ($def['fields'] as $f) {
            $parts = [$f['type'] === 'checkbox' ? 'nullable' : (!empty($f['required']) ? 'required' : 'nullable')];
            if ($f['type'] === 'checkbox') {
                $parts[] = 'boolean';
            } elseif ($f['type'] === 'select') {
                $parts[] = Rule::in(array_keys($f['options']));
            } else {
                $parts[] = 'string';
                $parts[] = 'max:' . ($f['max'] ?? 255);
            }
            $rules[$f['name']] = $parts;
        }
        if (isset($def['override'])) {
            $rules = array_replace($rules, ($def['override'])($request));
        }
        return $rules;
    }

    /** Applies the same normalisation as the seller's own controllers. */
    public static function prepare(array $def, array $data, Request $request): array
    {
        foreach ($def['fields'] as $f) {
            if ($f['type'] === 'checkbox') {
                $data[$f['name']] = $request->boolean($f['name']);
            }
        }
        if ($def['model'] === Experience::class && !empty($data['is_current'])) {
            $data['end_date'] = null;
        }
        return $data;
    }
}
