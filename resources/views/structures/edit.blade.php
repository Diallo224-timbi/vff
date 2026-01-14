@include('structures.form', [
            'structure' => $structure,
            'action' => route('structures.update', $structure),
            'method' => 'PUT'
        ])
      </div>
    </div>
  </div>