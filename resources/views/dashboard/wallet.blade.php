<x-layouts.dashboard>
  <x-slot name="title">Portefeuille</x-slot>
  <x-slot name="subtitle">Gérez vos dépôts et retraits</x-slot>

  <!-- Create Wallet Modal -->
  @if ($isWalletNull)
  <div id="walletModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg max-w-sm w-full p-8">
      <div class="text-center">
        <span class="material-symbols-outlined text-[48px] text-blue-600 dark:text-blue-400 block mb-4">account_balance_wallet</span>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Créer votre portefeuille</h2>
        <p class="text-slate-600 dark:text-slate-400 mb-6">Commencez par créer un portefeuille pour démarrer vos transactions.</p>
        
        <form action="{{ route('wallet.create') }}" method="POST" class="space-y-4">
          @csrf
          <button 
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center gap-2"
          >
            <span class="material-symbols-outlined">check_circle</span>
            Créer mon portefeuille
          </button>
        </form>
      </div> 
    </div> 
  </div>
  @endif

  <!-- Balance Card -->
  <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-8 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Solde disponible</p>
        <h2 class="text-4xl font-bold text-slate-900 dark:text-white">{{ number_format($isWalletNull ? 0 : $wallet->balance, 2) }} <span class="text-xl">{{ $isWalletNull ? 'USD' : ($wallet->currency ?? 'USD') }}</span></h2>
      </div>
      <div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Total déposé</p>
        <h3 class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($isWalletNull ? 0 : $wallet->total_deposited, 2) }} {{ $isWalletNull ? 'USD' : ($wallet->currency ?? 'USD') }}</h3>
      </div>
    </div>
  </div>

  <!-- Deposit, Withdraw & Transfer — hauteur uniforme via flex -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 lg:items-stretch">

    <!-- ─── DÉPÔT ─────────────────────────────────────────────── -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-8 flex flex-col">
      <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 shrink-0">
        <span class="material-symbols-outlined text-green-600">arrow_downward</span>
        Dépôt
      </h3>

      <form id="depositForm" class="flex flex-col flex-1">
        @csrf

        <!-- Champs (flex-1 pour pousser le bouton en bas) -->
        <div class="flex flex-col gap-4 flex-1">

          <!-- Moyen de paiement -->
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Moyen de paiement <span class="text-red-500">*</span>
            </label>
            @if ($paymentMethods->isEmpty())
              <p class="text-sm text-slate-600 dark:text-slate-400 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                <span class="material-symbols-outlined text-[18px] align-middle">info</span>
                Aucun moyen de paiement configuré.
              </p>
            @else
              <select name="payment_method_id" id="depositMethod"
                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                required onchange="renderPaymentInfo('depositMethod','deposit')">
                <option value="">Sélectionner un moyen</option>
                @foreach ($paymentMethods as $method)
                  <option value="{{ $method->id }}" data-payment="{{ json_encode([
                      'label' => $method->label,
                      'type' => $method->type,
                      'details' => $method->details,
                      'instructions' => $method->instructions,
                      'address' => $method->address,
                      'numero' => $method->numero,
                      'qrcode' => $method->qrcode,
                  ], JSON_UNESCAPED_UNICODE) }}">
                    {{ $method->label }} ({{ $method->type }})
                  </option>
                @endforeach
              </select>
            @endif
          </div>

          <!-- Informations du moyen de paiement -->
          @if (!$paymentMethods->isEmpty())
            @include('dashboard.partials.payment-method-info', ['prefix' => 'deposit'])
          @endif

          <!-- Montant -->
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Montant <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input type="number" name="amount" id="depositAmount"
                step="0.01" min="0.01" placeholder="0.00"
                class="w-full pl-4 pr-14 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                required oninput="updateDepositSummary()" />
              <span class="absolute right-4 top-2 text-slate-600 dark:text-slate-400">{{ $wallet->currency ?? 'USD' }}</span>
            </div>
          </div>

          <!-- Récapitulatif dépôt -->
          <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600 dark:text-slate-400">Montant versé</span>
              <span id="depSummaryAmount" class="text-slate-900 dark:text-white font-medium">0.00</span>
            </div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600 dark:text-slate-400">Frais de dépôt</span>
              <span class="text-green-600 dark:text-green-400 font-medium">Gratuit</span>
            </div>
            <div class="border-t border-slate-200 dark:border-slate-700 pt-2 flex justify-between text-sm">
              <span class="font-semibold text-slate-900 dark:text-white">Montant crédité</span>
              <span id="depSummaryNet" class="font-semibold text-green-600 dark:text-green-400">0.00</span>
            </div>
          </div>

          <!-- Info délai -->
          <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
            <p class="text-sm text-green-800 dark:text-green-300 flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px]">schedule</span>
              <span>Validation sous <strong>24 – 48 h</strong> par notre équipe</span>
            </p>
          </div>

        </div><!-- /champs -->

        <!-- Bouton aligné en bas -->
        <button type="submit"
          class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 shrink-0">
          <span class="material-symbols-outlined">send</span>
          Effectuer un dépôt
        </button>
      </form>
    </div>

    <!-- ─── RETRAIT ────────────────────────────────────────────── -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-8 flex flex-col">
      <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 shrink-0">
        <span class="material-symbols-outlined text-blue-600">arrow_upward</span>
        Retrait
      </h3>

      <form id="withdrawForm" class="flex flex-col flex-1">
        @csrf

        <div class="flex flex-col gap-4 flex-1">

          <!-- Méthode de retrait (3 options statiques) -->
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
              Méthode de retrait <span class="text-red-500">*</span>
            </label>
            <div class="space-y-2">
              <label class="flex items-center gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:border-blue-400 dark:hover:border-blue-600 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-950/20">
                <input type="radio" name="payment_method" value="bank_transfer" class="w-4 h-4 accent-blue-600" required onchange="updateWithdrawFields()">
                <div>
                  <p class="font-medium text-slate-900 dark:text-white">Virement bancaire</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">Virement direct sur votre compte bancaire</p>
                </div>
              </label>

              <label class="flex items-center gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:border-blue-400 dark:hover:border-blue-600 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-950/20">
                <input type="radio" name="payment_method" value="cryptocurrency" class="w-4 h-4 accent-blue-600" required onchange="updateWithdrawFields()">
                <div>
                  <p class="font-medium text-slate-900 dark:text-white">Cryptomonnaie</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">Retrait vers votre portefeuille crypto</p>
                </div>
              </label>

              <label class="flex items-center gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:border-blue-400 dark:hover:border-blue-600 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-950/20">
                <input type="radio" name="payment_method" value="card" class="w-4 h-4 accent-blue-600" required onchange="updateWithdrawFields()">
                <div>
                  <p class="font-medium text-slate-900 dark:text-white">Retrait par Carte bancaire</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">Retrait sur votre carte bancaire</p>
                </div>
              </label>
            </div>
          </div>

          <!-- Champs dynamiques selon la méthode -->
          <div id="withdrawFieldsContainer">
            
            <!-- Virement bancaire -->
            <div id="fields_bank_transfer" class="withdraw-fields hidden bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-600 rounded-lg p-4 space-y-4">
              <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Détails de votre compte</p>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Titulaire du compte <span class="text-red-500">*</span>
                </label>
                <input type="text" name="bank_account_holder" placeholder="Votre nom complet" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    IBAN / Numéro de compte <span class="text-red-500">*</span>
                  </label>
                  <input type="text" name="bank_account_number" placeholder="FR1420041010050500013M02606" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    BIC / SWIFT
                  </label>
                  <input type="text" name="bank_swift" placeholder="BNPAFRPP" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Nom de la banque <span class="text-red-500">*</span>
                </label>
                <input type="text" name="bank_name" placeholder="Ex: BNP Paribas" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
              </div>
            </div>

            <!-- Cryptomonnaie -->
            <div id="fields_cryptocurrency" class="withdraw-fields hidden bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-600 rounded-lg p-4 space-y-4">
              <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Détails du portefeuille</p>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Cryptomonnaie <span class="text-red-500">*</span>
                </label>
                <select name="crypto_type" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                  <option value="">Sélectionner...</option>
                  <option value="bitcoin">Bitcoin (BTC)</option>
                  <option value="ethereum">Ethereum (ETH)</option>
                  <option value="litecoin">Litecoin (LTC)</option>
                  <option value="ripple">Ripple (XRP)</option>
                  <option value="dogecoin">Dogecoin (DOGE)</option>
                  <option value="usdt">Tether (USDT)</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Adresse du portefeuille <span class="text-red-500">*</span>
                </label>
                <textarea name="crypto_address" rows="3" placeholder="Copiez-collez l'adresse complète de votre portefeuille" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs" disabled></textarea>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Vérifiez 2 fois l'adresse avant de soumettre</p>
              </div>
            </div>

            <!-- Carte Bancaire -->
            <div id="fields_card" class="withdraw-fields hidden bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-600 rounded-lg p-4 space-y-4">
              <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Détails de la carte</p>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Titulaire de la carte <span class="text-red-500">*</span>
                </label>
                <input type="text" name="card_holder_name" placeholder="Votre nom complet" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Numéro de carte (4 derniers chiffres) <span class="text-red-500">*</span>
                  </label>
                  <input type="text" name="card_number" placeholder="4242" maxlength="4" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Expiration (MM/YY) <span class="text-red-500">*</span>
                  </label>
                  <input type="text" name="card_expiry" placeholder="12/25" maxlength="5" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Banque émettrice <span class="text-red-500">*</span>
                </label>
                <input type="text" name="card_bank_name" placeholder="Ex: BNP Paribas" class="withdraw-input w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
              </div>
            </div>

          </div>

          <!-- Montant -->
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Montant <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input type="number" name="amount" id="withdrawAmount"
                step="0.01" min="0.01"
                max="{{ $isWalletNull ? 0 : $wallet->balance }}"
                placeholder="0.00"
                class="w-full pl-4 pr-14 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                required oninput="updateWithdrawFees()" />
              <span class="absolute right-4 top-2 text-slate-600 dark:text-slate-400">{{ $isWalletNull ? 'USD' : ($wallet->currency ?? 'USD') }}</span>
            </div>
          </div>

          <!-- Récapitulatif frais -->
          <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600 dark:text-slate-400">Montant</span>
              <span id="feeAmount" class="text-slate-900 dark:text-white font-medium">0.00</span>
            </div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600 dark:text-slate-400">Frais (1%)</span>
              <span id="feePercent" class="text-orange-600 dark:text-orange-400 font-medium">0.00</span>
            </div>
            <div class="border-t border-slate-200 dark:border-slate-700 pt-2 flex justify-between text-sm">
              <span class="font-semibold text-slate-900 dark:text-white">Vous recevrez</span>
              <span id="netAmount" class="font-semibold text-green-600 dark:text-green-400">0.00</span>
            </div>
          </div>

          <!-- Info solde + délai -->
          <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
            <p class="text-sm text-blue-800 dark:text-blue-300 flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
              <span>Solde disponible : <strong>{{ number_format($isWalletNull ? 0 : $wallet->balance, 2) }} {{ $isWalletNull ? 'USD' : ($wallet->currency ?? 'USD') }}</strong></span>
            </p>
          </div>

        </div><!-- /champs -->

        <button type="submit"
          class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 shrink-0 {{ ($isWalletNull || $wallet->balance == 0) ? 'opacity-50 cursor-not-allowed' : '' }}"
          {{ ($isWalletNull || $wallet->balance == 0) ? 'disabled' : '' }}>
          <span class="material-symbols-outlined">send</span>
          Demander un retrait
        </button>
      </form>
    </div>

    <!-- ─── TRANSFERT ──────────────────────────────────────────── -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-8 flex flex-col">
      <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 shrink-0">
        <span class="material-symbols-outlined text-purple-600">swap_horiz</span>
        Transfert Gratuit
      </h3>

      <form id="transferForm" class="flex flex-col flex-1">
        @csrf

        <div class="flex flex-col gap-4 flex-1">

          <!-- Email destinataire -->
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Email du destinataire <span class="text-red-500">*</span>
            </label>
            <input type="email" id="recipientEmail" name="recipient_email"
              placeholder="exemple@mail.com"
              class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
              required />
            <small class="text-slate-500 dark:text-slate-400 mt-1 block">Le destinataire doit avoir un compte Purprime Fox</small>
          </div>

          <!-- Montant -->
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Montant <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input type="number" id="transferAmount" name="amount"
                step="0.01" min="0.01"
                max="{{ $isWalletNull ? 0 : $wallet->balance }}"
                placeholder="0.00"
                class="w-full pl-4 pr-14 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                oninput="validateTransferAmount()" required />
              <span class="absolute right-4 top-2 text-slate-600 dark:text-slate-400">{{ $isWalletNull ? 'USD' : ($wallet->currency ?? 'USD') }}</span>
            </div>
            <p id="transferAmountError" class="hidden text-sm text-red-500 mt-1">Le montant dépasse votre solde disponible.</p>
          </div>

          <!-- Récapitulatif transfert -->
          <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600 dark:text-slate-400">Montant envoyé</span>
              <span id="trfSummaryAmount" class="text-slate-900 dark:text-white font-medium">0.00</span>
            </div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600 dark:text-slate-400">Frais</span>
              <span class="text-green-600 dark:text-green-400 font-medium">Gratuit</span>
            </div>
            <div class="border-t border-slate-200 dark:border-slate-700 pt-2 flex justify-between text-sm">
              <span class="font-semibold text-slate-900 dark:text-white">Solde disponible</span>
              <span class="font-semibold text-slate-700 dark:text-slate-300">{{ number_format($isWalletNull ? 0 : $wallet->balance, 2) }} {{ $isWalletNull ? 'USD' : ($wallet->currency ?? 'USD') }}</span>
            </div>
          </div>

          <!-- Info instantané -->
          <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3">
            <p class="text-sm text-purple-800 dark:text-purple-300 flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px]">bolt</span>
              <span>Transfert <strong>instantané</strong> et gratuit entre comptes</span>
            </p>
          </div>

        </div><!-- /champs -->

        <button id="transferSubmitBtn" type="submit"
          class="mt-4 w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 shrink-0 {{ ($isWalletNull || $wallet->balance == 0) ? 'opacity-50 cursor-not-allowed' : '' }}"
          {{ ($isWalletNull || $wallet->balance == 0) ? 'disabled' : '' }}>
          <span class="material-symbols-outlined">send</span>
          <span>Envoyer le transfert</span>
        </button>
      </form>
    </div>

  </div>

  <!-- ═══════════════════════════════════════════════════════════════ -->
  <!-- MODAL DE CONFIRMATION DU TRANSFERT                             -->
  <!-- ═══════════════════════════════════════════════════════════════ -->
  <div id="transferConfirmModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-md w-full p-8">

      <!-- Icône + titre -->
      <div class="text-center mb-6">
        <span class="material-symbols-outlined text-5xl text-purple-600 block mb-3">swap_horiz</span>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Confirmer le transfert</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Vérifiez les informations avant de confirmer</p>
      </div>

      <!-- Récapitulatif -->
      <div class="bg-slate-50 dark:bg-slate-900/60 rounded-lg p-4 mb-6 space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-slate-600 dark:text-slate-400">Destinataire</span>
          <span id="confirmRecipient" class="font-semibold text-slate-900 dark:text-white truncate max-w-[180px]">—</span>
        </div>
        <div class="flex justify-between">
          <span class="text-slate-600 dark:text-slate-400">Montant</span>
          <span id="confirmAmount" class="font-semibold text-slate-900 dark:text-white">—</span>
        </div>
        <div class="flex justify-between">
          <span class="text-slate-600 dark:text-slate-400">Frais</span>
          <span class="font-semibold text-green-600 dark:text-green-400">Gratuit</span>
        </div>
        <div class="border-t border-slate-200 dark:border-slate-700 pt-2 flex justify-between">
          <span class="font-bold text-slate-900 dark:text-white">Total débité</span>
          <span id="confirmTotal" class="font-bold text-purple-600 dark:text-purple-400">—</span>
        </div>
      </div>

      <!-- Checkboxes obligatoires -->
      <div class="space-y-3 mb-6">
        <label class="flex items-start gap-3 cursor-pointer">
          <input type="checkbox" id="checkIrreversible" class="mt-0.5 w-4 h-4 accent-purple-600" onchange="updateConfirmButton()">
          <span class="text-sm text-slate-700 dark:text-slate-300">
            Je comprends que cette opération est <strong>irréversible</strong> et que les fonds seront transférés immédiatement.
          </span>
        </label>
        <label class="flex items-start gap-3 cursor-pointer">
          <input type="checkbox" id="checkEmail" class="mt-0.5 w-4 h-4 accent-purple-600" onchange="updateConfirmButton()">
          <span class="text-sm text-slate-700 dark:text-slate-300">
            Je confirme que l'adresse email du destinataire est <strong>correcte</strong>.
          </span>
        </label>
      </div>

      <!-- Boutons -->
      <div class="flex gap-3">
        <button
          type="button"
          onclick="closeConfirmModal()"
          class="flex-1 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
        >
          Annuler
        </button>
        <button
          id="confirmTransferBtn"
          type="button"
          onclick="executeTransfer()"
          disabled
          class="flex-1 bg-purple-600 text-white font-semibold py-2.5 rounded-lg transition-colors opacity-50 cursor-not-allowed"
        >
          Confirmer
        </button>
      </div>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════════════════════════ -->
  <!-- MODAL DE CONFIRMATION GÉNÉRIQUE (Dépôt / Retrait)               -->
  <!-- ═══════════════════════════════════════════════════════════════ -->
  <div id="actionConfirmModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-md w-full p-8">

      <div class="text-center mb-6">
        <span id="actionConfirmIcon" class="material-symbols-outlined text-5xl text-blue-600 block mb-3">help</span>
        <h3 id="actionConfirmTitle" class="text-xl font-bold text-slate-900 dark:text-white">Confirmer</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Vérifiez les informations avant de confirmer</p>
      </div>

      <div id="actionConfirmRecap" class="bg-slate-50 dark:bg-slate-900/60 rounded-lg p-4 mb-6 space-y-2 text-sm"></div>

      <div class="flex gap-3">
        <button type="button" onclick="closeActionConfirmModal()"
          class="flex-1 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
          Annuler
        </button>
        <button id="actionConfirmBtn" type="button"
          class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors">
          Confirmer
        </button>
      </div>
    </div>
  </div>

  <!-- Toast Container -->
  <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

  <!-- Result Modal (Dépôt / Retrait / Transfert) -->
  <div id="resultModal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg max-w-sm w-full p-8">
      <div id="resultModalContent" class="text-center">
        <!-- Will be populated by JavaScript -->
      </div>
    </div>
  </div>

  <!-- Transaction History -->
  <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
      <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <span class="material-symbols-outlined">history</span>
        Historique des transactions
      </h3>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-50 dark:bg-slate-900/50">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Date</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Type</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Montant</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Méthode</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Statut</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
          @forelse ($transactions as $transaction)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
              <td class="px-6 py-4 text-sm text-slate-900 dark:text-white">
                {{ $transaction->created_at->format('d/m/Y H:i') }}
              </td>
              <td class="px-6 py-4 text-sm">
                @php
                  $typeColors = [
                    'deposit' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                    'withdraw' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                    'transfer' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                  ];
                  $typeLabels = [
                    'deposit' => 'Dépôt',
                    'withdraw' => 'Retrait',
                    'transfer' => 'Transfert',
                  ];
                  $type = $transaction->type;
                  $colorClass = $typeColors[$type] ?? 'bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400';
                  $label = $typeLabels[$type] ?? ucfirst($type);
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                  {{ $label }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
                {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
              </td>
              <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                {{ $transaction->method === 'internal_transfer' ? 'Transfert interne' : ($transaction->method ?? 'N/A') }}
              </td>
              <td class="px-6 py-4 text-sm">
                @if ($transaction->status === 'pending')
                  <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">En attente</span>
                @elseif ($transaction->status === 'completed')
                  <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Approuvé</span>
                @else
                  <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Rejeté</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                Aucune transaction
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <style>
    @keyframes slide-in {
      from { transform: translateX(400px); opacity: 0; }
      to   { transform: translateX(0);     opacity: 1; }
    }
    .animate-slide-in { animation: slide-in 0.3s ease-out; }

    /* Masquer les spin buttons natifs des inputs number */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type="number"] { -moz-appearance: textfield; }
  </style>
  <script>
    // ═════════════════════════════════════════════════════════════
    // CONSTANTES SOLDE
    // ═════════════════════════════════════════════════════════════
    const WALLET_BALANCE = {{ $isWalletNull ? 0 : (float)$wallet->balance }};
    const WALLET_CURRENCY = '{{ $isWalletNull ? "USD" : ($wallet->currency ?? "USD") }}';

    // ═════════════════════════════════════════════════════════════
    // INFOS MOYEN DE PAIEMENT (Dépôt / Retrait)
    // ═════════════════════════════════════════════════════════════
    const PAYMENT_TYPE_LABELS = {
      bank_transfer: 'Virement',
      crypto_usdt:   'USDT',
      crypto_btc:    'Bitcoin',
      crypto_eth:    'Ethereum',
      crypto_other:  'Crypto',
      mobile_money:  'Mobile Money',
      card:          'Carte',
      paypal:        'PayPal',
      other:         'Autre',
    };

    function renderPaymentInfo(selectId, prefix) {
      const select = document.getElementById(selectId);
      const card   = document.getElementById(prefix + 'PaymentInfo');
      if (!select || !card) return;

      const option = select.options[select.selectedIndex];
      if (!select.value || !option) { card.classList.add('hidden'); return; }

      let data = {};
      try { data = JSON.parse(option.getAttribute('data-payment')) || {}; } catch {}

      document.getElementById(prefix + 'PayLabel').textContent = data.label || '—';
      document.getElementById(prefix + 'PayTypeBadge').textContent = PAYMENT_TYPE_LABELS[data.type] || data.type || '—';

      // Instructions
      const instructionsRow = document.getElementById(prefix + 'PayInstructionsRow');
      if (data.instructions) {
        document.getElementById(prefix + 'PayInstructions').textContent = data.instructions;
        instructionsRow.classList.remove('hidden');
      } else {
        instructionsRow.classList.add('hidden');
      }

      // Adresse
      const addressRow = document.getElementById(prefix + 'PayAddressRow');
      if (data.address) {
        document.getElementById(prefix + 'PayAddressValue').textContent = data.address;
        addressRow.classList.remove('hidden');
      } else {
        addressRow.classList.add('hidden');
      }

      // Numéro de compte / téléphone
      const numeroRow = document.getElementById(prefix + 'PayNumeroRow');
      if (data.numero) {
        document.getElementById(prefix + 'PayNumeroValue').textContent = data.numero;
        numeroRow.classList.remove('hidden');
      } else {
        numeroRow.classList.add('hidden');
      }

      // QR code (généré côté client à partir du texte)
      const qrRow    = document.getElementById(prefix + 'PayQrRow');
      const qrCanvas = document.getElementById(prefix + 'PayQrCanvas');
      if (data.qrcode) {
        qrCanvas.innerHTML = '';
        new QRCode(qrCanvas, { text: data.qrcode, width: 140, height: 140 });
        qrRow.classList.remove('hidden');
      } else {
        qrRow.classList.add('hidden');
      }

      // Autres informations (details)
      const detailsRow  = document.getElementById(prefix + 'PayDetailsRow');
      const detailsList = document.getElementById(prefix + 'PayDetailsList');
      const details = data.details && typeof data.details === 'object' ? Object.entries(data.details) : [];
      if (details.length) {
        detailsList.innerHTML = '';
        details.forEach(([key, value]) => {
          const li = document.createElement('li');
          li.className = 'flex justify-between gap-2 text-slate-600 dark:text-slate-400';
          const k = document.createElement('span');
          k.className = 'font-medium text-slate-700 dark:text-slate-300';
          k.textContent = key;
          const v = document.createElement('span');
          v.className = 'text-right break-all';
          v.textContent = value;
          li.append(k, v);
          detailsList.appendChild(li);
        });
        detailsList.classList.add('hidden');
        document.getElementById(prefix + 'PayDetailsIcon').textContent = 'expand_more';
        detailsRow.classList.remove('hidden');
      } else {
        detailsRow.classList.add('hidden');
      }

      card.classList.remove('hidden');
    }

    function togglePayDetails(prefix) {
      const list = document.getElementById(prefix + 'PayDetailsList');
      const icon = document.getElementById(prefix + 'PayDetailsIcon');
      const hidden = list.classList.contains('hidden');
      list.classList.toggle('hidden', !hidden);
      icon.textContent = hidden ? 'expand_less' : 'expand_more';
    }

    function copyToClipboard(text, btnEl) {
      if (!text) return;
      navigator.clipboard.writeText(text).then(() => {
        const icon = btnEl.querySelector('.material-symbols-outlined');
        const original = icon.textContent;
        icon.textContent = 'check';
        showToast('Copié dans le presse-papiers', 'success', 1500);
        setTimeout(() => { icon.textContent = original; }, 1500);
      });
    }

    // ═════════════════════════════════════════════════════════════
    // DÉPÔT — récapitulatif
    // ═════════════════════════════════════════════════════════════
    function updateDepositSummary() {
      const amount = parseFloat(document.getElementById('depositAmount')?.value) || 0;
      document.getElementById('depSummaryAmount').textContent = amount.toFixed(2);
      document.getElementById('depSummaryNet').textContent    = amount.toFixed(2);
    }

    // ═════════════════════════════════════════════════════════════
    // RETRAIT — frais
    // ═════════════════════════════════════════════════════════════
    function updateWithdrawFees() {
      const input  = document.getElementById('withdrawAmount');
      const amount = parseFloat(input?.value) || 0;
      const fees   = amount * 0.01;
      document.getElementById('feeAmount').textContent  = amount.toFixed(2);
      document.getElementById('feePercent').textContent = fees.toFixed(2);
      document.getElementById('netAmount').textContent  = (amount - fees).toFixed(2);
    }

    // ═════════════════════════════════════════════════════════════
    // TRANSFERT — validation montant + mise à jour récap
    // ═════════════════════════════════════════════════════════════
    function validateTransferAmount() {
      const input  = document.getElementById('transferAmount');
      const error  = document.getElementById('transferAmountError');
      const btn    = document.getElementById('transferSubmitBtn');
      const val    = parseFloat(input.value) || 0;
      const over   = val > WALLET_BALANCE || val <= 0;
      error.classList.toggle('hidden', !over);
      input.classList.toggle('border-red-500', over);
      if (btn) btn.disabled = over;
      // Mise à jour récap
      const recap = document.getElementById('trfSummaryAmount');
      if (recap) recap.textContent = val > 0 ? val.toFixed(2) : '0.00';
      return !over;
    }

    // ═════════════════════════════════════════════════════════════
    // TOAST
    // ═════════════════════════════════════════════════════════════
    function showToast(message, type = 'info', duration = 3500) {
      const container = document.getElementById('toastContainer');
      const toast     = document.createElement('div');
      const colors    = { success: 'bg-green-500', error: 'bg-red-500', warning: 'bg-amber-500', info: 'bg-blue-500', loading: 'bg-slate-600' };
      const icons     = { success: 'check_circle', error: 'error', warning: 'warning', info: 'info', loading: 'hourglass_empty' };
      toast.className = `${colors[type] || colors.info} text-white px-5 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in`;
      toast.innerHTML = `<span class="material-symbols-outlined text-[20px]">${icons[type]}</span><span>${message}</span><button onclick="this.parentElement.remove()" class="ml-2 text-white/70 hover:text-white"><span class="material-symbols-outlined text-[18px]">close</span></button>`;
      container.appendChild(toast);
      if (type !== 'loading') setTimeout(() => toast.remove(), duration);
      return toast;
    }

    // ═════════════════════════════════════════════════════════════
    // MODAL CONFIRMATION
    // ═════════════════════════════════════════════════════════════
    function openConfirmModal(email, amount) {
      document.getElementById('confirmRecipient').textContent = email;
      document.getElementById('confirmAmount').textContent    = parseFloat(amount).toFixed(2) + ' ' + WALLET_CURRENCY;
      document.getElementById('confirmTotal').textContent     = parseFloat(amount).toFixed(2) + ' ' + WALLET_CURRENCY;
      document.getElementById('checkIrreversible').checked   = false;
      document.getElementById('checkEmail').checked          = false;
      document.getElementById('confirmTransferBtn').disabled  = true;
      document.getElementById('confirmTransferBtn').classList.add('opacity-50', 'cursor-not-allowed');
      document.getElementById('transferConfirmModal').classList.remove('hidden');
    }

    function closeConfirmModal() {
      document.getElementById('transferConfirmModal').classList.add('hidden');
    }

    function updateConfirmButton() {
      const ok  = document.getElementById('checkIrreversible').checked
               && document.getElementById('checkEmail').checked;
      const btn = document.getElementById('confirmTransferBtn');
      btn.disabled = !ok;
      btn.classList.toggle('opacity-50',      !ok);
      btn.classList.toggle('cursor-not-allowed', !ok);
      btn.classList.toggle('hover:bg-purple-700', ok);
    }

    // ═════════════════════════════════════════════════════════════
    // MODAL RÉSULTAT
    // ═════════════════════════════════════════════════════════════
    function showResultModal(status, data = {}) {
      const modal   = document.getElementById('resultModal');
      const content = document.getElementById('resultModalContent');
      if (status === 'success') {
        const title = data.title || 'Transfert réussi !';
        const body  = data.bodyHtml || `${data.amount} ${WALLET_CURRENCY} envoyé à <strong>${data.email}</strong>`;
        content.innerHTML = `
          <span class="material-symbols-outlined text-6xl text-green-500 block mb-3">check_circle</span>
          <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">${title}</h3>
          <p class="text-slate-600 dark:text-slate-400 mb-2">${body}</p>
          <p class="text-xs text-slate-500 dark:text-slate-500 mb-5">Réf : <code class="bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded">${data.ref}</code></p>
          <button onclick="closeResultModal(true)" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition-colors">Fermer</button>`;
      } else {
        const title = data.title || 'Erreur';
        content.innerHTML = `
          <span class="material-symbols-outlined text-6xl text-red-500 block mb-3">error</span>
          <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">${title}</h3>
          <p class="text-slate-600 dark:text-slate-400 mb-5">${data.message || 'Une erreur est survenue.'}</p>
          <button onclick="closeResultModal(false)" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg transition-colors">Fermer</button>`;
      }
      modal.classList.remove('hidden');
    }

    function closeResultModal(reset = false) {
      document.getElementById('resultModal').classList.add('hidden');
      if (reset) {
        // Recharger la page pour actualiser le solde et l'historique
        setTimeout(() => location.reload(), 300);
      }
    }

    document.getElementById('resultModal').addEventListener('click', function(e) {
      if (e.target === this) closeResultModal(false);
    });

    document.getElementById('transferConfirmModal').addEventListener('click', function(e) {
      if (e.target === this) closeConfirmModal();
    });

    // ═════════════════════════════════════════════════════════════
    // MODAL CONFIRMATION GÉNÉRIQUE (Dépôt / Retrait)
    // ═════════════════════════════════════════════════════════════
    const ACTION_COLOR_THEMES = {
      green: { icon: 'text-green-600', btn: 'bg-green-600 hover:bg-green-700' },
      blue:  { icon: 'text-blue-600',  btn: 'bg-blue-600 hover:bg-blue-700' },
    };

    let currentActionConfig = null;

    function openActionConfirmModal(cfg) {
      currentActionConfig = cfg;

      document.getElementById('actionConfirmTitle').textContent = cfg.title;

      const icon  = document.getElementById('actionConfirmIcon');
      const theme = ACTION_COLOR_THEMES[cfg.color] || ACTION_COLOR_THEMES.blue;
      icon.textContent = cfg.icon;
      icon.className   = `material-symbols-outlined text-5xl block mb-3 ${theme.icon}`;

      const recap = document.getElementById('actionConfirmRecap');
      recap.innerHTML = '';
      (cfg.recap || []).forEach(row => {
        const line  = document.createElement('div');
        line.className = 'flex justify-between';
        const label = document.createElement('span');
        label.className = 'text-slate-600 dark:text-slate-400';
        label.textContent = row.label;
        const value = document.createElement('span');
        value.className = 'font-semibold text-slate-900 dark:text-white';
        value.textContent = row.value;
        line.append(label, value);
        recap.appendChild(line);
      });

      const btn = document.getElementById('actionConfirmBtn');
      btn.className = `flex-1 text-white font-semibold py-2.5 rounded-lg transition-colors ${theme.btn}`;

      document.getElementById('actionConfirmModal').classList.remove('hidden');
    }

    function closeActionConfirmModal() {
      document.getElementById('actionConfirmModal').classList.add('hidden');
    }

    document.getElementById('actionConfirmModal').addEventListener('click', function(e) {
      if (e.target === this) closeActionConfirmModal();
    });

    document.getElementById('actionConfirmBtn').addEventListener('click', function() {
      if (currentActionConfig) submitAction(currentActionConfig);
    });

    async function submitAction(cfg) {
      closeActionConfirmModal();

      const btn     = document.querySelector(`#${cfg.formId} button[type="submit"]`);
      const btnHTML = btn?.innerHTML;
      if (btn) {
        btn.disabled  = true;
        btn.innerHTML = '<span class="material-symbols-outlined" style="animation:spin 1s linear infinite;display:inline-block">hourglass_empty</span> Traitement...';
      }

      const loadingToast = showToast(cfg.loadingMessage, 'loading');

      try {
        const response = await fetch(cfg.url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          },
          body: JSON.stringify(cfg.payload),
        });

        let data = {};
        try { data = await response.json(); } catch {}
        loadingToast.remove();

        if (response.ok) {
          showToast(cfg.successMessage, 'success', 3000);
          showResultModal('success', {
            title:    cfg.successTitle,
            bodyHtml: cfg.successBody,
            ref:      data.data?.reference ?? '—',
          });
        } else {
          const msg = data.message || (data.errors ? Object.values(data.errors).flat()[0] : null) || 'Une erreur est survenue.';
          showToast(msg, 'error', 4000);
          showResultModal('error', { title: cfg.errorTitle, message: msg });
          if (btn) { btn.disabled = false; btn.innerHTML = btnHTML; }
        }
      } catch (err) {
        loadingToast.remove();
        const msg = 'Erreur de connexion. Vérifiez votre réseau.';
        showToast(msg, 'error', 4000);
        showResultModal('error', { title: cfg.errorTitle, message: msg });
        if (btn) { btn.disabled = false; btn.innerHTML = btnHTML; }
      }
    }

    // ═════════════════════════════════════════════════════════════
    // DÉPÔT — soumission formulaire
    // ═════════════════════════════════════════════════════════════
    document.getElementById('depositForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const select = document.getElementById('depositMethod');
      const amount = parseFloat(document.getElementById('depositAmount').value);

      if (!select || !select.value) {
        showToast('Veuillez sélectionner un moyen de paiement.', 'warning');
        return;
      }
      if (!amount || amount <= 0) {
        showToast('Veuillez indiquer un montant valide.', 'warning');
        return;
      }

      const methodLabel = select.options[select.selectedIndex].textContent.trim();

      openActionConfirmModal({
        title: 'Confirmer le dépôt',
        icon: 'arrow_downward',
        color: 'green',
        recap: [
          { label: 'Moyen de paiement', value: methodLabel },
          { label: 'Montant', value: `${amount.toFixed(2)} ${WALLET_CURRENCY}` },
        ],
        url: '{{ route("wallet.deposit.store") }}',
        payload: { payment_method_id: select.value, amount: amount },
        loadingMessage: 'Envoi de la demande de dépôt…',
        successMessage: 'Demande de dépôt envoyée !',
        successTitle: 'Dépôt soumis !',
        successBody: `${amount.toFixed(2)} ${WALLET_CURRENCY} — en attente de validation`,
        errorTitle: 'Dépôt refusé',
        formId: 'depositForm',
      });
    });

    // ═════════════════════════════════════════════════════════════
    // RETRAIT — Gestion dynamique des champs selon la méthode
    // ═════════════════════════════════════════════════════════════
    function updateWithdrawFields() {
      const method = document.querySelector('input[name="payment_method"]:checked')?.value;
      
      // Masquer tous les blocs et désactiver leurs champs
      document.querySelectorAll('.withdraw-fields').forEach(el => {
        el.classList.add('hidden');
        el.querySelectorAll('input, select, textarea').forEach(input => {
          input.disabled = true;
          input.required = false;
        });
      });

      if (!method) return;

      // Afficher le bloc actif et activer ses champs
      const activeContainer = document.getElementById('fields_' + method);
      if (activeContainer) {
        activeContainer.classList.remove('hidden');
        activeContainer.querySelectorAll('input, select, textarea').forEach(input => {
          input.disabled = false;
          if (input.classList.contains('withdraw-input')) {
            input.required = true;
          }
        });
      }
    }

    // ═════════════════════════════════════════════════════════════
    // RETRAIT — soumission formulaire
    // ═════════════════════════════════════════════════════════════
    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const method = document.querySelector('input[name="payment_method"]:checked')?.value;
      const amount = parseFloat(document.getElementById('withdrawAmount').value);

      if (!method) {
        showToast('Veuillez sélectionner une méthode de retrait.', 'warning');
        return;
      }
      if (!amount || amount <= 0) {
        showToast('Veuillez indiquer un montant valide.', 'warning');
        return;
      }
      if (amount > WALLET_BALANCE) {
        showToast('Le montant dépasse votre solde disponible.', 'warning');
        return;
      }

      // Valider les champs spécifiques à la méthode
      const form = new FormData(this);
      let validationError = null;

      if (method === 'bank_transfer') {
        if (!form.get('bank_account_holder') || !form.get('bank_account_number') || !form.get('bank_name')) {
          validationError = 'Veuillez remplir tous les champs bancaires.';
        }
      } else if (method === 'cryptocurrency') {
        if (!form.get('crypto_type') || !form.get('crypto_address')) {
          validationError = 'Veuillez remplir tous les champs de cryptomonnaie.';
        }
      } else if (method === 'card') {
        if (!form.get('card_holder_name') || !form.get('card_number') || !form.get('card_expiry') || !form.get('card_bank_name')) {
          validationError = 'Veuillez remplir tous les champs de la carte.';
        }
      }

      if (validationError) {
        showToast(validationError, 'warning');
        return;
      }

      const fees = amount * 0.01;
      const net  = amount - fees;
      const methodLabels = {
        bank_transfer: 'Virement bancaire',
        cryptocurrency: 'Cryptomonnaie',
        card: 'Retrait par Carte'
      };

      // Construire le payload manuellement (FormData exclut les champs disabled)
      const withdrawPayload = {
        payment_method: method,
        amount: amount,
      };
      if (method === 'bank_transfer') {
        withdrawPayload.bank_account_holder = document.querySelector('#fields_bank_transfer input[name="bank_account_holder"]')?.value || '';
        withdrawPayload.bank_account_number = document.querySelector('#fields_bank_transfer input[name="bank_account_number"]')?.value || '';
        withdrawPayload.bank_swift          = document.querySelector('#fields_bank_transfer input[name="bank_swift"]')?.value || '';
        withdrawPayload.bank_name           = document.querySelector('#fields_bank_transfer input[name="bank_name"]')?.value || '';
      } else if (method === 'cryptocurrency') {
        withdrawPayload.crypto_type    = document.querySelector('#fields_cryptocurrency select[name="crypto_type"]')?.value || '';
        withdrawPayload.crypto_address = document.querySelector('#fields_cryptocurrency textarea[name="crypto_address"]')?.value || '';
      } else if (method === 'card') {
        withdrawPayload.card_holder_name = document.querySelector('#fields_card input[name="card_holder_name"]')?.value || '';
        withdrawPayload.card_number      = document.querySelector('#fields_card input[name="card_number"]')?.value || '';
        withdrawPayload.card_expiry      = document.querySelector('#fields_card input[name="card_expiry"]')?.value || '';
        withdrawPayload.card_bank_name   = document.querySelector('#fields_card input[name="card_bank_name"]')?.value || '';
      }

      openActionConfirmModal({
        title: 'Confirmer le retrait',
        icon: 'arrow_upward',
        color: 'blue',
        recap: [
          { label: 'Méthode', value: methodLabels[method] },
          { label: 'Montant', value: `${amount.toFixed(2)} ${WALLET_CURRENCY}` },
          { label: 'Frais (1%)', value: `${fees.toFixed(2)} ${WALLET_CURRENCY}` },
          { label: 'Vous recevrez', value: `${net.toFixed(2)} ${WALLET_CURRENCY}` },
        ],
        url: '{{ route("wallet.withdraw.store") }}',
        payload: withdrawPayload,
        loadingMessage: 'Envoi de la demande de retrait…',
        successMessage: 'Demande de retrait envoyée !',
        successTitle: 'Retrait soumis !',
        successBody: `${amount.toFixed(2)} ${WALLET_CURRENCY} — en attente de validation`,
        errorTitle: 'Retrait refusé',
        formId: 'withdrawForm',
      });
    });

    // ═════════════════════════════════════════════════════════════
    // TRANSFERT — soumission formulaire → ouvre la confirmation
    // ═════════════════════════════════════════════════════════════
    document.getElementById('transferForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const email  = document.getElementById('recipientEmail').value.trim();
      const amount = parseFloat(document.getElementById('transferAmount').value);

      if (!email || !amount || amount <= 0) {
        showToast('Veuillez remplir tous les champs.', 'warning');
        return;
      }
      if (!validateTransferAmount()) return;

      openConfirmModal(email, amount);
    });

    // ═════════════════════════════════════════════════════════════
    // TRANSFERT — exécution après confirmation
    // ═════════════════════════════════════════════════════════════
    async function executeTransfer() {
      closeConfirmModal();

      const email   = document.getElementById('recipientEmail').value.trim();
      const amount  = parseFloat(document.getElementById('transferAmount').value);
      const btn     = document.getElementById('transferSubmitBtn');
      const btnHTML = btn.innerHTML;

      btn.disabled  = true;
      btn.innerHTML = '<span class="material-symbols-outlined" style="animation:spin 1s linear infinite;display:inline-block">hourglass_empty</span> Traitement...';

      const loadingToast = showToast('Traitement du transfert…', 'loading');

      try {
        const response = await fetch('{{ route("wallet.transfer.store") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          },
          body: JSON.stringify({ recipient_email: email, amount: amount }),
        });

        let data = {};
        try { data = await response.json(); } catch {}
        loadingToast.remove();

        if (response.ok) {
          showToast('Transfert effectué avec succès !', 'success', 3000);
          showResultModal('success', {
            amount: amount.toFixed(2),
            email:  email,
            ref:    data.data?.sender_transaction?.reference ?? '—',
          });
        } else {
          const msg = data.message || 'Erreur lors du transfert.';
          showToast(msg, 'error', 4000);
          showResultModal('error', { message: msg });
          btn.disabled  = false;
          btn.innerHTML = btnHTML;
        }
      } catch (err) {
        loadingToast.remove();
        const msg = 'Erreur de connexion. Vérifiez votre réseau.';
        showToast(msg, 'error', 4000);
        showResultModal('error', { message: msg });
        btn.disabled  = false;
        btn.innerHTML = btnHTML;
      }
    }

    // ═════════════════════════════════════════════════════════════
    // INIT
    // ═════════════════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function() {
      updateDepositSummary();
      updateWithdrawFees();
      validateTransferAmount();
    });
  </script>
  @endpush
</x-layouts.dashboard>
