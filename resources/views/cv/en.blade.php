<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Resume - {{ $name }}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.5; color: #111827; padding: 28px 32px; }
  h1 { font-size: 22px; letter-spacing: 0.5px; text-transform: uppercase; }
  .headline { font-size: 13px; font-weight: bold; color: #1d4ed8; margin-top: 2px; }
  .contact { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 10.5px; color: #374151; }
  .contact td { padding: 1px 12px 1px 0; vertical-align: top; }
  .contact a { color: #1d4ed8; text-decoration: none; }
  h2 { font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.8px; color: #111827; border-bottom: 2px solid #1d4ed8; padding-bottom: 3px; margin: 16px 0 8px; }
  p.summary { text-align: justify; }
  .skill-group { margin-bottom: 4px; }
  .skill-group strong { display: inline; }
  .item { margin-bottom: 9px; }
  .item-head { font-weight: bold; font-size: 11.5px; }
  .item-meta { font-size: 10.5px; color: #4b5563; margin-bottom: 2px; }
  ul { margin: 2px 0 0 16px; }
  li { margin-bottom: 1px; }
  .tech { font-size: 10.5px; color: #374151; }
</style>
</head>
<body>
  <h1>{{ $name }}</h1>
  <div class="headline">{{ $roleLabel }}</div>
  <table class="contact">
    <tr>
      <td>{{ $location }}</td>
      <td><a href="mailto:{{ $email }}">{{ $email }}</a></td>
      <td><a href="https://wa.me/{{ $waNumber }}">{{ $phoneDisplay }}</a></td>
    </tr>
    <tr>
      @if($github)<td><a href="{{ $github }}">{{ $github }}</a></td>@endif
      @if($linkedin)<td><a href="{{ $linkedin }}">{{ $linkedin }}</a></td>@endif
      @if($portfolioUrl)<td><a href="{{ $portfolioUrl }}">{{ $portfolioUrl }}</a></td>@endif
    </tr>
  </table>

  <h2>Summary</h2>
  <p class="summary">{{ $summary }}</p>

  <h2>Skills</h2>
  @foreach($skillGroups as $group)
  <div class="skill-group"><strong>{{ $group['label'] }}:</strong> {{ implode(', ', $group['items']) }}</div>
  @endforeach

  <h2>Work Experience</h2>
  @foreach($experience as $job)
  <div class="item">
    <div class="item-head">{{ $job['title'] }} - {{ $job['org'] }}</div>
    <div class="item-meta">{{ $job['period'] }}</div>
    <div>{{ $job['desc'] }}</div>
  </div>
  @endforeach

  <h2>Selected Projects</h2>
  @foreach($projects as $project)
  <div class="item">
    <div class="item-head">{{ $project['name'] }}</div>
    <div class="item-meta">{{ $project['meta'] }}</div>
    <div class="tech">Tech: {{ implode(', ', $project['tech']) }}</div>
    <ul>
      @foreach($project['bullets'] as $bullet)
      <li>{{ $bullet }}</li>
      @endforeach
    </ul>
  </div>
  @endforeach

  <h2>Education</h2>
  @foreach($education as $edu)
  <div class="item">
    <div class="item-head">{{ $edu['title'] }} - {{ $edu['org'] }}</div>
    <div class="item-meta">{{ $edu['period'] }}</div>
    <div>{{ $edu['desc'] }}</div>
  </div>
  @endforeach

  <h2>Organizations</h2>
  <ul>
    @foreach($organizations as $org)
    <li>{{ $org }}</li>
    @endforeach
  </ul>
</body>
</html>
