<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'processed_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'publishedBy');
    }

    // ========================================
    // Role Checking Methods
    // ========================================
    
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRegistrar(): bool
    {
        return $this->role === 'registrar';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Permission helper - can access admin panel
    public function canAccessAdminPanel(): bool
    {
        return in_array($this->role, ['admin', 'registrar', 'cashier']);
    }

    // Can edit student information
    public function canEditStudents(): bool
    {
        return $this->role === 'admin';
    }

    // Can edit/delete enrollments
    public function canEditEnrollments(): bool
    {
        return $this->role === 'admin';
    }

    // Can upload documents (all staff)
    public function canUploadDocuments(): bool
    {
        return in_array($this->role, ['admin', 'registrar', 'cashier']);
    }

    // Can access academics section
    public function canAccessAcademics(): bool
    {
        return $this->role === 'admin';
    }

    // Can access financials section
    public function canAccessFinancials(): bool
    {
        return $this->role === 'admin';
    }

    // Can access billing and payments (cashier, registrar, admin)
    public function canAccessBillingPayments(): bool
    {
        return in_array($this->role, ['admin', 'registrar', 'cashier']);
    }

    // Can access system settings
    public function canAccessSystem(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
