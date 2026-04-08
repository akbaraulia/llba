@extends('layouts.app')

@section('title', 'Data Pembeli')
@section('page_title', 'Data Pembeli dan Pembayaran')

@php
    $baseTotal = (float) $checkout['total'];
    $pointRedeemValue = max((float) $settings->point_redeem_value, 0.01);
    $pointEarnSpend = max((float) $settings->point_earn_spend, 0.01);
    $defaultMaxRedeemPercentage = (float) $settings->default_max_redeem_percentage;
@endphp

@section('content')
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel-card p-3 p-lg-4">
                <h5 class="mb-3">Ringkasan Belanja</h5>
                <div class="table-responsive mb-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checkout['items'] as $item)
                                <tr>
                                    <td>{{ $item['product_name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-secondary mb-0">
                    Total Awal: <strong id="totalAwal" data-total="{{ $baseTotal }}">Rp
                        {{ number_format($baseTotal, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <form method="POST" action="{{ route('purchases.process') }}" class="panel-card p-3 p-lg-4 needs-validation"
                novalidate>
                @csrf
                <h5 class="mb-3">Data Pembeli</h5>

                <div class="mb-3">
                    <label class="form-label d-block">Status Member</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('member_status') is-invalid @enderror" type="radio"
                            name="member_status" id="nonMember" value="non_member"
                            {{ old('member_status', 'non_member') === 'non_member' ? 'checked' : '' }}>
                        <label class="form-check-label" for="nonMember">Bukan Member</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('member_status') is-invalid @enderror" type="radio"
                            name="member_status" id="member" value="member"
                            {{ old('member_status') === 'member' ? 'checked' : '' }}>
                        <label class="form-check-label" for="member">Member</label>
                    </div>
                    @error('member_status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div id="nonMemberFields"
                    class="{{ old('member_status', 'non_member') === 'non_member' ? '' : 'd-none' }}">
                    <div class="mb-3">
                        <label class="form-label">Uang Dibayar <span class="text-danger">*</span></label>
                        <input type="text" inputmode="numeric" name="cash_paid" id="cashPaidNonMember"
                            class="form-control @error('cash_paid') is-invalid @enderror" placeholder="Contoh: Rp 100.000"
                            value="{{ old('cash_paid') }}">
                        @error('cash_paid')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Uang dibayar harus diisi!</div>
                        @enderror
                        <div class="small mt-2">Estimasi Kembalian: <strong id="estimatedChangeNonMember">Rp 0</strong>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('purchases.create') }}" class="btn btn-outline-dark">Kembali</a>
                        <button class="btn btn-dark">Beli</button>
                    </div>
                </div>

                <div id="memberFlow" class="{{ old('member_status') === 'member' ? '' : 'd-none' }}">
                    <div id="memberStep1">
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon Member <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" id="customerPhone"
                                class="form-control @error('customer_phone') is-invalid @enderror"
                                placeholder="Contoh: 081234567890"
                                value="{{ old('customer_phone', $member?->user?->phone) }}">
                            @error('customer_phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Nomor telepon member harus diisi!</div>
                            @enderror
                            <small class="text-muted">Masukkan nomor telepon member yang terdaftar.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Uang Dibayar <span class="text-danger">*</span></label>
                            <input type="text" inputmode="numeric" name="cash_paid" id="cashPaidMember"
                                class="form-control @error('cash_paid') is-invalid @enderror"
                                placeholder="Contoh: Rp 100.000" value="{{ old('cash_paid') }}">
                            @error('cash_paid')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Uang dibayar harus diisi!</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('purchases.create') }}" class="btn btn-outline-dark">Kembali</a>
                            <button type="button" class="btn btn-dark" id="toMemberStep2">Lanjut</button>
                        </div>
                    </div>

                    <div id="memberStep2" class="d-none">
                        <div class="alert alert-info small" id="memberNotFound" style="display:none;">
                            Member tidak ditemukan. Kembali ke langkah 1 dan cek nomor telepon.
                        </div>

                        <div id="memberSummary" class="mb-3" style="display:none;">
                            <div class="mb-2">Nama Member: <strong id="memberName">-</strong></div>
                            <div class="mb-2">Poin Saat Ini: <strong id="memberPoints">0</strong></div>
                            <div class="small text-muted" id="memberRuleText"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Gunakan Poin?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('use_points') is-invalid @enderror" type="radio"
                                    name="use_points" id="usePointsNo" value="no"
                                    {{ old('use_points', 'no') === 'no' ? 'checked' : '' }}>
                                <label class="form-check-label" for="usePointsNo">Tidak</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('use_points') is-invalid @enderror" type="radio"
                                    name="use_points" id="usePointsYes" value="yes"
                                    {{ old('use_points') === 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="usePointsYes">Ya</label>
                            </div>
                            @error('use_points')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 d-none" id="redeemWrapper">
                            <label class="form-label">Redeem Poin</label>
                            <input type="number" min="0" name="points_used" id="pointsUsed"
                                class="form-control @error('points_used') is-invalid @enderror"
                                value="{{ old('points_used', 0) }}">
                            @error('points_used')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div>Total Setelah Poin: <strong id="totalAfter">Rp
                                    {{ number_format($baseTotal, 0, ',', '.') }}</strong></div>
                            <div>Estimasi Poin Didapat: <strong
                                    id="earnedPoints">{{ (int) floor($baseTotal / $pointEarnSpend) }}</strong></div>
                            <div>Estimasi Kembalian: <strong id="estimatedChangeMember">Rp 0</strong></div>
                            <div id="insufficientText" class="text-danger small mt-1 d-none">Uang dibayar masih kurang
                                dari total yang harus dibayar.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-dark" id="backToMemberStep1">Kembali Langkah
                                1</button>
                            <button class="btn btn-dark">Beli</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const memberRadio = document.getElementById('member');
        const nonMemberRadio = document.getElementById('nonMember');
        const nonMemberFields = document.getElementById('nonMemberFields');
        const memberFlow = document.getElementById('memberFlow');
        const memberStep1 = document.getElementById('memberStep1');
        const memberStep2 = document.getElementById('memberStep2');
        const toMemberStep2 = document.getElementById('toMemberStep2');
        const backToMemberStep1 = document.getElementById('backToMemberStep1');
        const customerPhone = document.getElementById('customerPhone');
        const memberName = document.getElementById('memberName');
        const memberPoints = document.getElementById('memberPoints');
        const memberRuleText = document.getElementById('memberRuleText');
        const memberSummary = document.getElementById('memberSummary');
        const memberNotFound = document.getElementById('memberNotFound');
        const pointsUsed = document.getElementById('pointsUsed');
        const cashPaidNonMember = document.getElementById('cashPaidNonMember');
        const cashPaidMember = document.getElementById('cashPaidMember');
        const usePointsYes = document.getElementById('usePointsYes');
        const usePointsNo = document.getElementById('usePointsNo');
        const redeemWrapper = document.getElementById('redeemWrapper');
        const totalAwal = Number(document.getElementById('totalAwal').dataset.total || 0);
        const totalAfter = document.getElementById('totalAfter');
        const earnedPoints = document.getElementById('earnedPoints');
        const estimatedChangeNonMember = document.getElementById('estimatedChangeNonMember');
        const estimatedChangeMember = document.getElementById('estimatedChangeMember');
        const insufficientText = document.getElementById('insufficientText');
        const memberDirectory = @json($memberDirectory);

        const pointRedeemValue = {{ $pointRedeemValue }};
        const pointEarnSpend = {{ $pointEarnSpend }};
        const defaultMaxRedeemPercentage = {{ $defaultMaxRedeemPercentage }};
        const initialMemberStatus = '{{ old('member_status', 'non_member') }}';
        const initialCustomerPhone = '{{ old('customer_phone', $member?->user?->phone) }}';

        const rupiah = (value) => 'Rp ' + Number(value).toLocaleString('id-ID');
        const moneyToNumber = (value) => Number(String(value || '').replace(/[^0-9]/g, '')) || 0;

        const formatMoneyInput = (input) => {
            const digits = String(input.value || '').replace(/[^0-9]/g, '');
            input.value = digits !== '' ? rupiah(Number(digits)) : '';
        };

        const normalizePhone = (phone) => String(phone || '').replace(/\D+/g, '');

        const findMemberByPhone = (phone) => {
            const normalizedPhone = normalizePhone(phone);
            if (!normalizedPhone) return null;

            return memberDirectory.find((item) => normalizePhone(item.phone) === normalizedPhone) || null;
        };

        let currentMemberData = null;
        let currentMaxPoints = 0;

        const updateMemberData = () => {
            const memberData = findMemberByPhone(customerPhone.value);
            currentMemberData = memberData;
            currentMaxPoints = 0;

            if (!memberData) {
                memberSummary.style.display = 'none';
                memberNotFound.style.display = 'block';
                pointsUsed.value = 0;
                pointsUsed.max = 0;
                return;
            }

            memberNotFound.style.display = 'none';
            memberSummary.style.display = 'block';

            const effectiveMaxRedeemPercentage = memberData.max_redeem_percentage !== null ?
                Number(memberData.max_redeem_percentage) :
                defaultMaxRedeemPercentage;

            const maxDiscountAmount = totalAwal * (effectiveMaxRedeemPercentage / 100);
            const maxPointsByPercent = Math.floor(maxDiscountAmount / pointRedeemValue);

            currentMaxPoints = Math.max(0, Math.min(Number(memberData.points || 0), maxPointsByPercent));

            memberName.textContent = memberData.name || '-';
            memberPoints.textContent = String(memberData.points || 0);
            memberRuleText.textContent =
                `Rate saat ini: 1 poin = ${rupiah(pointRedeemValue)}. Batas pemakaian poin ${effectiveMaxRedeemPercentage.toFixed(2)}%. Maksimal redeem saat ini ${currentMaxPoints} poin.`;

            pointsUsed.max = String(currentMaxPoints);
            if (Number(pointsUsed.value || 0) > currentMaxPoints) {
                pointsUsed.value = currentMaxPoints;
            }
        };

        const getActiveCashInput = () => memberRadio.checked ? cashPaidMember : cashPaidNonMember;

        const syncCashValue = (fromInput, toInput) => {
            if (fromInput && toInput) {
                toInput.value = fromInput.value;
            }
        };

        const toggleRedeem = () => {
            const isUsePoints = usePointsYes.checked;
            redeemWrapper.classList.toggle('d-none', !isUsePoints);
            if (!isUsePoints) {
                pointsUsed.value = 0;
            }
        };

        const recalc = () => {
            let usedPoints = Number(pointsUsed.value || 0);

            if (!memberRadio.checked || !usePointsYes.checked || !currentMemberData) {
                usedPoints = 0;
            }

            if (usedPoints < 0) usedPoints = 0;
            if (usedPoints > currentMaxPoints) usedPoints = currentMaxPoints;
            pointsUsed.value = usedPoints;

            const discountAmount = usedPoints * pointRedeemValue;
            const finalTotal = Math.max(totalAwal - discountAmount, 0);
            totalAfter.textContent = rupiah(finalTotal);
            earnedPoints.textContent = Math.floor(finalTotal / pointEarnSpend);

            const paid = moneyToNumber(getActiveCashInput()?.value);
            const changeAmount = Math.max(paid - finalTotal, 0);

            estimatedChangeNonMember.textContent = rupiah(changeAmount);
            estimatedChangeMember.textContent = rupiah(changeAmount);

            if (paid > 0 && paid < finalTotal) {
                insufficientText.classList.remove('d-none');
            } else {
                insufficientText.classList.add('d-none');
            }
        };

        const openMemberStep1 = () => {
            memberStep1.classList.remove('d-none');
            memberStep2.classList.add('d-none');
        };

        const openMemberStep2 = () => {
            updateMemberData();

            if (!currentMemberData) {
                memberStep1.classList.add('d-none');
                memberStep2.classList.remove('d-none');
                toggleRedeem();
                recalc();
                return;
            }

            memberStep1.classList.add('d-none');
            memberStep2.classList.remove('d-none');
            toggleRedeem();
            recalc();
        };

        const toggleMemberFlow = () => {
            if (memberRadio.checked) {
                nonMemberFields.classList.add('d-none');
                memberFlow.classList.remove('d-none');
                cashPaidNonMember.disabled = true;
                cashPaidNonMember.required = false;
                cashPaidMember.disabled = false;
                cashPaidMember.required = true;
                customerPhone.required = true;
                usePointsYes.required = true;
                usePointsNo.required = true;
                syncCashValue(cashPaidNonMember, cashPaidMember);
                openMemberStep1();
            } else {
                memberFlow.classList.add('d-none');
                nonMemberFields.classList.remove('d-none');
                cashPaidMember.disabled = true;
                cashPaidMember.required = false;
                cashPaidNonMember.disabled = false;
                cashPaidNonMember.required = true;
                customerPhone.required = false;
                usePointsYes.required = false;
                usePointsNo.required = false;
                syncCashValue(cashPaidMember, cashPaidNonMember);
                pointsUsed.value = 0;
            }

            recalc();
        };

        toMemberStep2.addEventListener('click', openMemberStep2);
        backToMemberStep1.addEventListener('click', openMemberStep1);
        customerPhone.addEventListener('input', updateMemberData);
        customerPhone.addEventListener('change', updateMemberData);

        memberRadio.addEventListener('change', toggleMemberFlow);
        nonMemberRadio.addEventListener('change', toggleMemberFlow);
        usePointsYes.addEventListener('change', () => {
            toggleRedeem();
            recalc();
        });
        usePointsNo.addEventListener('change', () => {
            toggleRedeem();
            recalc();
        });

        pointsUsed.addEventListener('input', recalc);
        cashPaidNonMember.addEventListener('input', () => {
            formatMoneyInput(cashPaidNonMember);
            recalc();
        });
        cashPaidMember.addEventListener('input', () => {
            formatMoneyInput(cashPaidMember);
            recalc();
        });

        if (initialCustomerPhone) {
            customerPhone.value = initialCustomerPhone;
        }

        if (cashPaidNonMember.value) {
            formatMoneyInput(cashPaidNonMember);
        }

        if (cashPaidMember.value) {
            formatMoneyInput(cashPaidMember);
        }

        if (initialMemberStatus === 'member') {
            memberRadio.checked = true;
            toggleMemberFlow();
            openMemberStep2();
        } else {
            nonMemberRadio.checked = true;
            toggleMemberFlow();
        }

        toggleRedeem();
        recalc();
    </script>
@endpush
