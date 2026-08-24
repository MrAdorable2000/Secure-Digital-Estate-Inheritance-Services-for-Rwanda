<?php
declare(strict_types=1);

class HomeController extends Controller
{
    public function index(array $params = []): void
    {
        // If already logged in, redirect to dashboard
        try {
            $a = auth();
            if ($a && $a->check()) {
                $this->redirect('dashboard');
                return;
            }
        } catch (Throwable) {
            // Auth unavailable — treat as guest
        }
        $this->view('home/index', [
            'pageTitle' => 'R-DEIP — Rwanda Digital Estate & Inheritance Platform',
        ]);
    }

    public function about(array $params = []): void
    {
        $this->view('home/about', [
            'pageTitle' => 'About R-DEIP',
        ]);
    }

    public function services(array $params = []): void
    {
        $this->view('home/services', [
            'pageTitle' => 'Services',
        ]);
    }

    public function howItWorks(array $params = []): void
    {
        $this->view('home/how-it-works', [
            'pageTitle' => 'How It Works',
        ]);
    }

    public function contact(array $params = []): void
    {
        $this->view('home/contact', [
            'pageTitle' => 'Contact Us',
        ]);
    }
}
