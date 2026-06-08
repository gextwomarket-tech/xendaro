<x-layouts.dashboard>
  <x-slot name="title">Vérification KYC</x-slot>

  <div class="max-w-2xl mx-auto">

    {{-- Alert succès après soumission (style Bootstrap) --}}
    @if (session('kyc_submitted'))
      <div class="mb-6 flex items-start gap-3 p-4 rounded-xl border border-green-300 bg-green-50 dark:bg-green-900/25 dark:border-green-700 text-green-800 dark:text-green-200 animate-fadeIn" role="alert">
        <span class="material-symbols-outlined text-green-600 dark:text-green-400 mt-0.5">check_circle</span>
        <div class="flex-1">
          <p class="font-semibold text-sm">Documents envoyés avec succès !</p>
          <p class="text-sm mt-0.5 opacity-80">Notre équipe examinera vos documents sous <strong>1 à 24 heures</strong>. Vous serez notifié par email dès que la vérification sera terminée.</p>
        </div>
        <button onclick="this.closest('[role=alert]').remove()" class="text-green-600 dark:text-green-400 hover:opacity-70 transition-opacity">
          <span class="material-symbols-outlined text-lg">close</span>
        </button>
      </div>
    @elseif (session('error'))
      <div class="mb-6 flex items-start gap-3 p-4 rounded-xl border border-red-300 bg-red-50 dark:bg-red-900/25 dark:border-red-700 text-red-800 dark:text-red-200 animate-fadeIn" role="alert">
        <span class="material-symbols-outlined text-red-600 dark:text-red-400 mt-0.5">error</span>
        <span class="flex-1 text-sm">{{ session('error') }}</span>
        <button onclick="this.closest('[role=alert]').remove()" class="text-red-600 hover:opacity-70 transition-opacity">
          <span class="material-symbols-outlined text-lg">close</span>
        </button>
      </div>
    @endif

    <!-- FORM STATE: aucun document soumis → afficher le formulaire -->
    @if ($showForm)
      <div class="animate-fadeIn">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-8">
          <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Vérification de votre identité</h1>
          <p class="text-slate-600 dark:text-slate-400 mb-6">Complétez ce formulaire pour accéder à toutes les fonctionnalités</p>

          <form action="{{ route('kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="kycForm" onsubmit="showLoadingToast()">
            @csrf

            <!-- Personal Info Section -->
            <div class="border-b border-slate-200 dark:border-slate-700 pb-6">
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">person</span>
                Informations personnelles
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Prénom <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    name="first_name" 
                    value="{{ old('first_name', $user->first_name) }}"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror"
                    required
                  />
                  @error('first_name')<span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Nom <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    name="last_name" 
                    value="{{ old('last_name', $user->last_name) }}"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror"
                    required
                  />
                  @error('last_name')<span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Date de naissance <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="date" 
                    name="date_of_birth" 
                    value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_of_birth') border-red-500 @enderror"
                    required
                  />
                  @error('date_of_birth')<span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Pays <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    name="country" 
                    value="{{ old('country', $user->country) }}"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @enderror"
                    required
                  />
                  @error('country')<span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Ville <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    name="city" 
                    value="{{ old('city', $user->city) }}"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror"
                    required
                  />
                  @error('city')<span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Adresse <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    name="address" 
                    value="{{ old('address', $user->address) }}"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                    required
                  />
                  @error('address')<span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>

            <!-- Documents Section -->
            <div>
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">upload_file</span>
                Documents
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Identity Document -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Pièce d'identité <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="file" 
                    name="identity_document" 
                    accept="image/*"
                    class="hidden" 
                    id="identity_file"
                    required
                  />
                  <label for="identity_file" class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 cursor-pointer transition-colors bg-slate-50 dark:bg-slate-900">
                    <div class="text-center">
                      <span class="material-symbols-outlined text-3xl text-slate-400 dark:text-slate-500 block mb-2">id_card</span>
                      <p class="text-sm text-slate-600 dark:text-slate-400">Cliquer ou glisser</p>
                      <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">PNG, JPG (Max 5MB)</p>
                    </div>
                  </label>
                  <div id="identity_preview" class="mt-2 hidden">
                    <img id="identity_img" src="" alt="Aperçu" class="w-full h-40 object-cover rounded-lg border border-slate-300 dark:border-slate-600" />
                  </div>
                  @error('identity_document')<span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <!-- Selfie Document -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Selfie avec pièce d'identité <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="file" 
                    name="selfie_with_identity" 
                    accept="image/*"
                    class="hidden" 
                    id="selfie_file"
                    required
                  />
                  <label for="selfie_file" class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 cursor-pointer transition-colors bg-slate-50 dark:bg-slate-900">
                    <div class="text-center">
                      <span class="material-symbols-outlined text-3xl text-slate-400 dark:text-slate-500 block mb-2">photo_camera</span>
                      <p class="text-sm text-slate-600 dark:text-slate-400">Cliquer ou glisser</p>
                      <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">PNG, JPG (Max 5MB)</p>
                    </div>
                  </label>
                  <div id="selfie_preview" class="mt-2 hidden">
                    <img id="selfie_img" src="" alt="Aperçu" class="w-full h-40 object-cover rounded-lg border border-slate-300 dark:border-slate-600" />
                  </div>
                  @error('selfie_with_identity')<span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
              <button 
                type="submit"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors flex items-center justify-center gap-2"
              >
                <span class="material-symbols-outlined">send</span>
                Soumettre
              </button>
            </div>

            <p class="text-xs text-slate-500 dark:text-slate-400 text-center">
              Vos données sont sécurisées et chiffrées.
            </p>
          </form>
        </div>
      </div>

    @elseif ($isPending)
      <!-- PENDING STATE -->
      <div class="animate-fadeIn">
        <div class="bg-amber-50 dark:bg-amber-900/30 border-2 border-amber-200 dark:border-amber-800 rounded-lg p-8 text-center">
          <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-amber-100 dark:bg-amber-800/50 flex items-center justify-center animate-pulse">
              <span class="material-symbols-outlined text-4xl text-amber-600 dark:text-amber-400">schedule</span>
            </div>
          </div>
          <h2 class="text-2xl font-bold text-amber-900 dark:text-amber-200 mb-2">Vérification en cours</h2>
          <p class="text-amber-800 dark:text-amber-300 mb-4">
            Votre demande a été reçue avec succès.
          </p>
          <div class="bg-white dark:bg-slate-800 rounded-lg p-4 inline-block">
            <p class="text-sm text-slate-600 dark:text-slate-400">Délai estimé:</p>
            <p class="text-lg font-bold text-amber-600 dark:text-amber-400">1 à 24 heures</p>
          </div>
        </div>
      </div>

    @elseif ($isVerified)
      <!-- VERIFIED STATE -->
      <div class="animate-fadeIn">
        <div class="bg-green-50 dark:bg-green-900/30 border-2 border-green-200 dark:border-green-800 rounded-lg p-8 text-center">
          <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-800/50 flex items-center justify-center">
              <span class="material-symbols-outlined text-4xl text-green-600 dark:text-green-400">verified</span>
            </div>
          </div>
          <h2 class="text-2xl font-bold text-green-900 dark:text-green-200 mb-2">Vérification approuvée</h2>
          <p class="text-green-800 dark:text-green-300">
            Votre compte est vérifié. Accédez à toutes les fonctionnalités!
          </p>
        </div>
      </div>

    @elseif ($isRejected)
      <!-- REJECTED STATE -->
      <div class="animate-fadeIn">
        <div class="bg-red-50 dark:bg-red-900/30 border-2 border-red-200 dark:border-red-800 rounded-lg p-8 text-center">
          <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-800/50 flex items-center justify-center">
              <span class="material-symbols-outlined text-4xl text-red-600 dark:text-red-400">cancel</span>
            </div>
          </div>
          <h2 class="text-2xl font-bold text-red-900 dark:text-red-200 mb-2">Vérification rejetée</h2>
          <p class="text-red-800 dark:text-red-300 mb-4">
            Votre demande a été rejetée.
          </p>
          @if ($kycDocuments->where('status', 'rejected')->first())
            <div class="bg-white dark:bg-slate-800 rounded-lg p-3 mb-4 text-left">
              <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold mb-2">Raison:</p>
              <p class="text-sm text-red-700 dark:text-red-400">
                {{ $kycDocuments->where('status', 'rejected')->first()->rejection_reason ?? 'Vérifiez les documents.' }}
              </p>
            </div>
          @endif
          <form action="{{ route('kyc.show') }}" method="GET">
            <button type="submit" class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors">
              Soumettre à nouveau
            </button>
          </form>
        </div>
      </div>
    @endif
  </div>

  @push('styles')
    <style>
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
      }
    </style>
  @endpush

  @push('scripts')
    <script>
      // File preview
      function setupFilePreview(inputId, previewId, imgId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        input.addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
              document.getElementById(imgId).src = event.target.result;
              document.getElementById(previewId).classList.remove('hidden');
            };
            reader.readAsDataURL(file);
          }
        });
      }

      setupFilePreview('identity_file', 'identity_preview', 'identity_img');
      setupFilePreview('selfie_file', 'selfie_preview', 'selfie_img');

      // Drag and drop
      function setupDragDrop(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        const label = input.nextElementSibling;
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
          label.addEventListener(event, (e) => e.preventDefault());
        });

        ['dragenter', 'dragover'].forEach(event => {
          label.addEventListener(event, () => {
            label.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
          });
        });

        ['dragleave', 'drop'].forEach(event => {
          label.addEventListener(event, () => {
            label.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
          });
        });

        label.addEventListener('drop', (e) => {
          input.files = e.dataTransfer.files;
          input.dispatchEvent(new Event('change'));
        });
      }

      setupDragDrop('identity_file');
      setupDragDrop('selfie_file');
    </script>
  @endpush
</x-layouts.dashboard>
