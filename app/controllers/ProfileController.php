<?php
declare(strict_types=1);

class ProfileController extends Controller
{
    public function index(array $params = []): void
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            $this->redirect('login');
            return;
        }
        $this->layout('app', 'profile/index', [
            'user' => $currentUser,
            'pageTitle' => 'My Profile',
        ]);
    }

    public function update(array $params = []): void
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            $this->redirect('login');
            return;
        }

        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name'  => $_POST['last_name'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'phone'      => $_POST['phone'] ?? '',
        ];

        $validator = new Validator($data, [
            'first_name' => 'required|min:2|max:100',
            'last_name'  => 'required|min:2|max:100',
            'email'      => 'required|email',
            'phone'      => 'phone|nullable',
        ]);

        if (!$validator->validate()) {
            Flash::set('error', 'Please fix the errors below.');
            Flash::setInputs($data);
            $this->back();
            return;
        }

        try {
            Database::update('users', $data, 'id = :uid', ['uid' => $currentUser['id']]);
            auth()->logAudit('profile.update', 'profile', 'Updated profile information');
            Flash::set('success', 'Profile updated successfully.');
        } catch (Throwable $e) {
            error_log('[PROFILE] ' . $e->getMessage());
            Flash::set('error', 'Failed to update profile. Please try again.');
        }

        $this->redirect('profile');
    }

    public function changePassword(array $params = []): void
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            $this->redirect('login');
            return;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['new_password_confirmation'] ?? '';

        if (!password_verify($currentPassword, $currentUser['password'])) {
            Flash::set('error', 'Current password is incorrect.');
            $this->back();
            return;
        }

        $data = ['new_password' => $newPassword, 'new_password_confirmation' => $confirmPassword];
        $validator = new Validator($data, [
            'new_password' => 'required|strong_password|confirmed',
        ]);

        if (!$validator->validate()) {
            Flash::set('error', $validator->firstError('new_password'));
            $this->back();
            return;
        }

        try {
            Database::update('users', [
                'password' => password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]),
                'updated_at' => date('Y-m-d H:i:s'),
            ], 'id = :uid', ['uid' => $currentUser['id']]);
            auth()->logAudit('password.change', 'profile', 'Changed account password');
            Flash::set('success', 'Password changed successfully.');
        } catch (Throwable $e) {
            error_log('[PROFILE] ' . $e->getMessage());
            Flash::set('error', 'Failed to change password.');
        }

        $this->redirect('profile');
    }
}
