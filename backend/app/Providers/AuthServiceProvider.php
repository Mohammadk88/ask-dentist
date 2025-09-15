<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Infrastructure\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Message;
use App\Models\ConsultationAttachment;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Infrastructure\Models\User::class => \App\Policies\UserPolicy::class,
        Doctor::class => \App\Policies\DoctorPolicy::class,
        Patient::class => \App\Policies\PatientPolicy::class,
        Consultation::class => \App\Policies\ConsultationPolicy::class,
        Message::class => \App\Policies\MessagePolicy::class,
        ConsultationAttachment::class => \App\Policies\ConsultationAttachmentPolicy::class,
        \App\Models\TreatmentRequest::class => \App\Policies\TreatmentRequestPolicy::class,
        \App\Models\Clinic::class => \App\Policies\ClinicPolicy::class,
        \App\Models\ProfilePatient::class => \App\Policies\ProfilePolicy::class,
        \App\Models\ProfileDoctor::class => \App\Policies\ProfilePolicy::class,
        \App\Infrastructure\Models\MedicalFile::class => \App\Policies\MedicalFilePolicy::class,
        'FavoriteDoctor' => \App\Policies\FavoriteDoctorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Configure Passport
        $this->configurePassport();

        // Define Gates for role-based access
        $this->defineGates();
    }

    /**
     * Configure Laravel Passport settings
     */
    private function configurePassport(): void
    {
        // Configure token expiration times
        Passport::tokensExpireIn(now()->addMinutes((int) config('passport.expiration_times.access_token', 1440)));
        Passport::refreshTokensExpireIn(now()->addMinutes((int) config('passport.expiration_times.refresh_token', 43200)));
        Passport::personalAccessTokensExpireIn(now()->addMinutes((int) config('passport.expiration_times.personal_access_token', 525600)));

        // Define token scopes
        Passport::tokensCan(config('passport.scopes', []));

        // Set default scope
        if ($defaultScope = config('passport.default_scope')) {
            Passport::setDefaultScope($defaultScope);
        }
    }

    /**
     * Define authorization gates based on roles
     */
    private function defineGates(): void
    {
        // Admin gates - full access
        Gate::define('admin-access', function (User $user) {
            return $user->hasRole('Admin');
        });

        Gate::define('manage-users', function (User $user) {
            return $user->hasRole(['Admin', 'ClinicManager']);
        });

        Gate::define('manage-doctors', function (User $user) {
            return $user->hasRole(['Admin', 'ClinicManager']);
        });

        Gate::define('manage-patients', function (User $user) {
            return $user->hasRole(['Admin', 'ClinicManager', 'Doctor']);
        });

        // Doctor-specific gates
        Gate::define('doctor-access', function (User $user) {
            return $user->hasRole('Doctor') && $user->doctor?->is_verified;
        });

        Gate::define('create-consultation-response', function (User $user) {
            return $user->hasRole('Doctor') && $user->doctor?->is_verified;
        });

        Gate::define('view-medical-records', function (User $user) {
            return $user->hasRole(['Doctor', 'Admin', 'ClinicManager']);
        });

        Gate::define('update-medical-records', function (User $user) {
            return $user->hasRole('Doctor') && $user->doctor?->is_verified;
        });

        // Patient-specific gates
        Gate::define('patient-access', function (User $user) {
            return $user->hasRole('Patient');
        });

        Gate::define('create-consultation', function (User $user) {
            return $user->hasRole('Patient') && $user->patient?->hasGivenConsent();
        });

        // Consultation access gates
        Gate::define('view-consultation', function (User $user, Consultation $consultation) {
            if ($user->hasRole('Admin')) {
                return true;
            }

            if ($user->hasRole('Doctor')) {
                return $consultation->doctor_id === $user->doctor?->id;
            }

            if ($user->hasRole('Patient')) {
                return $consultation->patient_id === $user->patient?->id;
            }

            return false;
        });

        Gate::define('assign-consultation', function (User $user) {
            return $user->hasRole(['Admin', 'ClinicManager', 'Doctor']) &&
                   (!$user->hasRole('Doctor') || $user->doctor?->is_verified);
        });

        // Message access gates
        Gate::define('send-message', function (User $user, Consultation $consultation) {
            return Gate::allows('view-consultation', $consultation);
        });

        Gate::define('view-messages', function (User $user, Consultation $consultation) {
            return Gate::allows('view-consultation', $consultation);
        });

        // File access gates
        Gate::define('upload-attachment', function (User $user, Consultation $consultation) {
            return Gate::allows('view-consultation', $consultation);
        });

        Gate::define('download-attachment', function (User $user, ConsultationAttachment $attachment) {
            return Gate::allows('view-consultation', $attachment->consultation);
        });

        // Default deny gate for sensitive actions
        Gate::before(function (User $user, string $ability) {
            // Log all gate checks for audit purposes
            // Log authorization checks (skip in testing to avoid database issues)
            // TEMPORARILY DISABLED due to UUID/bigint mismatch in activity_log table
            /*
            if (app()->environment() !== 'testing') {
                activity('security')
                    ->causedBy($user)
                    ->withProperties([
                        'ability' => $ability,
                        'user_roles' => $user->roles ? $user->roles->pluck('name') : [],
                        'timestamp' => now(),
                    ])
                    ->log('Gate authorization check');
            }
            */

            // Super admin override (if needed)
            if ($user->hasRole('Admin') && $user->hasPermissionTo('admin-access')) {
                return true;
            }

            // Continue with regular gate checks
            return null;
        });

        // After gate - log denied access attempts (skip in testing)
        Gate::after(function (User $user, string $ability, bool $result) {
            if (!in_array(app()->environment(), ['testing', 'dusk.local']) && !$result) {
                activity('security')
                    ->causedBy($user)
                    ->withProperties([
                        'ability' => $ability,
                        'result' => 'denied',
                        'user_roles' => $user->roles ? $user->roles->pluck('name') : [],
                        'timestamp' => now(),
                    ])
                    ->log('Access denied');
            }
        });

    }
}
