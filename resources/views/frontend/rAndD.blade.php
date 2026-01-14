@extends('frontend.layouts.master')

@section('content')


    <style>
        /* --- R&D Hero Header --- */
        .rd-hero {
            background-color: #00a651;
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .innovation-badge {
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 20px;
        }

        /* --- R&D Status Counters --- */
        .rd-counters {
            background: white;
            padding: 40px 0;
            border-bottom: 1px solid #eee;
        }

        .counter-item h2 {
            color: #00a651;
            font-weight: 800;
            margin-bottom: 5px;
        }

        /* --- Project Cards & Timelines --- */
        .project-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border: 1px solid #f0f0f0;
        }

        .project-img {
            height: 100%;
            min-height: 400px;
            object-fit: cover;
        }

        .status-label {
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-in-progress { background: #fff4e6; color: #d97706; }
        .status-testing { background: #f3e8ff; color: #7c3aed; }
        .status-planning { background: #e0f2fe; color: #0284c7; }

        /* Vertical Timeline */
        .rd-timeline {
            list-style: none;
            padding-left: 25px;
            position: relative;
            margin-top: 20px;
        }

        .rd-timeline::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 5px;
            bottom: 5px;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-point {
            position: relative;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .timeline-point::after {
            content: '';
            position: absolute;
            left: -25px;
            top: 6px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #d1d5db;
            border: 2px solid white;
        }

        .timeline-point.done::after {
            background: #00a651;
        }

        .timeline-point.done {
            color: #333;
            font-weight: 500;
        }
    </style>


<section class="rd-hero">
    <div class="container">
        <span class="innovation-badge">Innovation Hub</span>
        <h1 class="fw-bold display-4">Research & Development</h1>
        <p class="opacity-75 mx-auto" style="max-width: 700px;">
            Discover the innovative projects we're working on to shape the future of agriculture. 
            From concept to completion, follow our journey of innovation.
        </p>
    </div>
</section>

<section class="rd-counters">
    <div class="container">
        <div class="row text-center">
            <div class="col-3 counter-item"><h2>0</h2><p class="small text-muted mb-0">Completed Projects</p></div>
            <div class="col-3 counter-item"><h2>1</h2><p class="small text-muted mb-0">In Progress</p></div>
            <div class="col-3 counter-item"><h2>1</h2><p class="small text-muted mb-0">In Testing</p></div>
            <div class="col-3 counter-item"><h2>1</h2><p class="small text-muted mb-0">Planning</p></div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-success fw-bold small mb-1">OUR PROJECTS</p>
            <h2 class="fw-bold">Current R&D Initiatives</h2>
            <p class="text-muted">Explore the projects we're actively developing to bring new solutions to market.</p>
        </div>

        @forelse($data as $project)
        <div class="project-card mb-4"> {{-- Added mb-4 for spacing between cards --}}
            <div class="row g-0">
                <div class="col-lg-5">
                    {{-- Check if image exists, otherwise show placeholder --}}
                    <img src="{{ $project->feature_image ? asset($project->feature_image) : asset('images/placeholder.webp') }}" 
                         class="project-img w-100 h-100" 
                         style="object-fit: cover;"
                         alt="{{ $project->title }}">
                </div>
                <div class="col-lg-7 p-4 p-md-5">
                    <div class="mb-3">
                        <span class="status-label status-in-progress">
                            {{ $project->status == 1 ? 'In Progress' : 'Completed' }}
                        </span>
                        <span class="ms-3 text-muted small">
                            <i class="far fa-calendar"></i> 
                            Started {{ \Carbon\Carbon::parse($project->date)->format('M Y') }}
                        </span>
                    </div>
                    
                    <h3 class="fw-bold text-success mb-3">{{ $project->title }}</h3>
                    <p class="text-muted small">
                        {{ $project->short_description }}
                    </p>
                    
                    {{-- Timeline Section --}}
                    <h6 class="fw-bold small mt-4">Project Details</h6>
                    <div class="project-long-desc mb-3">
                        {{-- Rendering the HTML content from the long_description --}}
                        {!! Str::limit($project->long_description, 200) !!}
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <span class="text-muted small">Target Completion: </span>
                        <span class="text-success fw-bold">
                            {{ \Carbon\Carbon::parse($project->deadline)->format('F Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="text-center p-5">
                <p class="text-muted">No R&D projects found at the moment.</p>
            </div>
        @endforelse

        <div class="text-center mt-5 pt-5">
            <h2 class="fw-bold mb-3 text-success">Have an Idea for Innovation?</h2>
            <p class="text-muted mx-auto mb-4" style="max-width: 600px;">We're always looking for new challenges. If you have a problem that needs solving, we'd love to hear from you.</p>
            <a href="{{ url('/contact') }}" class="btn btn-success btn-lg px-5 py-3 shadow">Share Your Ideas</a>
        </div>
    </div>
</section>



@endsection

@section('script')


@endsection