<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Inward</h3>
                                <h3 v-else class="nk-block-title page-title">Add Outward</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <ul class="row g-gs preview-icon-svg">
                            <li class="col-lg-3 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="outward_salebill">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                <path d="M64.77,26.89,34.1,48,4.5,26.89,32.84,7.4a2.26,2.26,0,0,1,2.53,0Z" fill="#fff" />
                                                <path d="M4.42,26H64.56A1.45,1.45,0,0,1,66,27.44V60.58A1.43,1.43,0,0,1,64.58,62H4.44A1.45,1.45,0,0,1,3,60.56V27.42A1.43,1.43,0,0,1,4.42,26Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M18.51,36.11,28.66,16.77,45.6,25.49,37,41.91,35.1,40.79a1.39,1.39,0,0,0-1.55,0l-4.34,2.82Z" fill="#c4cefe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M26.52,41.7l3.33-14s18.24,4,18.7,4.35l-1.48,6.25-7.76,5.19-5.09-3.11-5,3.23Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="32.47" y1="32.04" x2="42.98" y2="34.5" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="31.64" y1="35.5" x2="42.16" y2="37.96" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.82" y1="38.96" x2="41.34" y2="41.41" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.17" y1="41.73" x2="40.68" y2="44.18" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="29.51" y1="44.5" x2="35.08" y2="45.8" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M64.64,26.43,34.1,47,4.64,26.43l28.21-19a2.29,2.29,0,0,1,2.52,0Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M65.72,61.48,35.71,41A2.31,2.31,0,0,0,33,40.92L3.71,61.36" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="#fff" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <ellipse cx="72.6" cy="48.73" rx="1.44" ry="1.42" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10" stroke-width="2" />
                                                <path d="M61.92,67.69l-8.41-5a1,1,0,0,1-.34-1.36l.31-.52a1,1,0,0,1,1.37-.34l8.41,5a1,1,0,0,1,.35,1.36l-.31.51A1,1,0,0,1,61.92,67.69Z" fill="#9cabff" />
                                                <line x1="77.65" y1="61.3" x2="59.79" y2="50.73" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="76.21" y1="63.69" x2="58.34" y2="53.13" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="74.76" y1="66.09" x2="56.9" y2="55.52" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="73.31" y1="68.49" x2="55.45" y2="57.92" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="68.01 52.34 61.48 48.47 61.48 48.47" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="67.67" y1="77.86" x2="49.8" y2="67.29" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="57.43 68.5 51.51 64.99 51.51 64.99" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Sale Bill Outward</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                            <li class="col-lg-3 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="outward_payment">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                <path d="M64.77,26.89,34.1,48,4.5,26.89,32.84,7.4a2.26,2.26,0,0,1,2.53,0Z" fill="#fff" />
                                                <path d="M4.42,26H64.56A1.45,1.45,0,0,1,66,27.44V60.58A1.43,1.43,0,0,1,64.58,62H4.44A1.45,1.45,0,0,1,3,60.56V27.42A1.43,1.43,0,0,1,4.42,26Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M18.51,36.11,28.66,16.77,45.6,25.49,37,41.91,35.1,40.79a1.39,1.39,0,0,0-1.55,0l-4.34,2.82Z" fill="#c4cefe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M26.52,41.7l3.33-14s18.24,4,18.7,4.35l-1.48,6.25-7.76,5.19-5.09-3.11-5,3.23Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="32.47" y1="32.04" x2="42.98" y2="34.5" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="31.64" y1="35.5" x2="42.16" y2="37.96" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.82" y1="38.96" x2="41.34" y2="41.41" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.17" y1="41.73" x2="40.68" y2="44.18" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="29.51" y1="44.5" x2="35.08" y2="45.8" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M64.64,26.43,34.1,47,4.64,26.43l28.21-19a2.29,2.29,0,0,1,2.52,0Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M65.72,61.48,35.71,41A2.31,2.31,0,0,0,33,40.92L3.71,61.36" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="#fff" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <ellipse cx="72.6" cy="48.73" rx="1.44" ry="1.42" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10" stroke-width="2" />
                                                <path d="M61.92,67.69l-8.41-5a1,1,0,0,1-.34-1.36l.31-.52a1,1,0,0,1,1.37-.34l8.41,5a1,1,0,0,1,.35,1.36l-.31.51A1,1,0,0,1,61.92,67.69Z" fill="#9cabff" />
                                                <line x1="77.65" y1="61.3" x2="59.79" y2="50.73" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="76.21" y1="63.69" x2="58.34" y2="53.13" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="74.76" y1="66.09" x2="56.9" y2="55.52" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="73.31" y1="68.49" x2="55.45" y2="57.92" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="68.01 52.34 61.48 48.47 61.48 48.47" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="67.67" y1="77.86" x2="49.8" y2="67.29" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="57.43 68.5 51.51 64.99 51.51 64.99" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Payment Outward</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                            <li class="col-lg-3 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="outward_commission">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                <path d="M64.77,26.89,34.1,48,4.5,26.89,32.84,7.4a2.26,2.26,0,0,1,2.53,0Z" fill="#fff" />
                                                <path d="M4.42,26H64.56A1.45,1.45,0,0,1,66,27.44V60.58A1.43,1.43,0,0,1,64.58,62H4.44A1.45,1.45,0,0,1,3,60.56V27.42A1.43,1.43,0,0,1,4.42,26Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M18.51,36.11,28.66,16.77,45.6,25.49,37,41.91,35.1,40.79a1.39,1.39,0,0,0-1.55,0l-4.34,2.82Z" fill="#c4cefe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M26.52,41.7l3.33-14s18.24,4,18.7,4.35l-1.48,6.25-7.76,5.19-5.09-3.11-5,3.23Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="32.47" y1="32.04" x2="42.98" y2="34.5" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="31.64" y1="35.5" x2="42.16" y2="37.96" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.82" y1="38.96" x2="41.34" y2="41.41" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.17" y1="41.73" x2="40.68" y2="44.18" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="29.51" y1="44.5" x2="35.08" y2="45.8" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M64.64,26.43,34.1,47,4.64,26.43l28.21-19a2.29,2.29,0,0,1,2.52,0Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M65.72,61.48,35.71,41A2.31,2.31,0,0,0,33,40.92L3.71,61.36" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="#fff" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <ellipse cx="72.6" cy="48.73" rx="1.44" ry="1.42" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10" stroke-width="2" />
                                                <path d="M61.92,67.69l-8.41-5a1,1,0,0,1-.34-1.36l.31-.52a1,1,0,0,1,1.37-.34l8.41,5a1,1,0,0,1,.35,1.36l-.31.51A1,1,0,0,1,61.92,67.69Z" fill="#9cabff" />
                                                <line x1="77.65" y1="61.3" x2="59.79" y2="50.73" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="76.21" y1="63.69" x2="58.34" y2="53.13" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="74.76" y1="66.09" x2="56.9" y2="55.52" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="73.31" y1="68.49" x2="55.45" y2="57.92" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="68.01 52.34 61.48 48.47 61.48 48.47" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="67.67" y1="77.86" x2="49.8" y2="67.29" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="57.43 68.5 51.51 64.99 51.51 64.99" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Commission Outward</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                            <li class="col-lg-3 col-6 col-sm-4">
                                <div class="preview-icon-box card card-bordered">
                                    <a v-bind:href="outward_commission_invoice">
                                        <div class="preview-icon-wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                <path d="M64.77,26.89,34.1,48,4.5,26.89,32.84,7.4a2.26,2.26,0,0,1,2.53,0Z" fill="#fff" />
                                                <path d="M4.42,26H64.56A1.45,1.45,0,0,1,66,27.44V60.58A1.43,1.43,0,0,1,64.58,62H4.44A1.45,1.45,0,0,1,3,60.56V27.42A1.43,1.43,0,0,1,4.42,26Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M18.51,36.11,28.66,16.77,45.6,25.49,37,41.91,35.1,40.79a1.39,1.39,0,0,0-1.55,0l-4.34,2.82Z" fill="#c4cefe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M26.52,41.7l3.33-14s18.24,4,18.7,4.35l-1.48,6.25-7.76,5.19-5.09-3.11-5,3.23Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <line x1="32.47" y1="32.04" x2="42.98" y2="34.5" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="31.64" y1="35.5" x2="42.16" y2="37.96" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.82" y1="38.96" x2="41.34" y2="41.41" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="30.17" y1="41.73" x2="40.68" y2="44.18" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="29.51" y1="44.5" x2="35.08" y2="45.8" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M64.64,26.43,34.1,47,4.64,26.43l28.21-19a2.29,2.29,0,0,1,2.52,0Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M65.72,61.48,35.71,41A2.31,2.31,0,0,0,33,40.92L3.71,61.36" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="#fff" />
                                                <path d="M66.89,82.72,45.69,70.18A2,2,0,0,1,45,67.45L62.07,39.13a2,2,0,0,1,2.75-.69L86,51a2,2,0,0,1,.7,2.72L69.64,82A2,2,0,0,1,66.89,82.72Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <ellipse cx="72.6" cy="48.73" rx="1.44" ry="1.42" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10" stroke-width="2" />
                                                <path d="M61.92,67.69l-8.41-5a1,1,0,0,1-.34-1.36l.31-.52a1,1,0,0,1,1.37-.34l8.41,5a1,1,0,0,1,.35,1.36l-.31.51A1,1,0,0,1,61.92,67.69Z" fill="#9cabff" />
                                                <line x1="77.65" y1="61.3" x2="59.79" y2="50.73" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="76.21" y1="63.69" x2="58.34" y2="53.13" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="74.76" y1="66.09" x2="56.9" y2="55.52" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="73.31" y1="68.49" x2="55.45" y2="57.92" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="68.01 52.34 61.48 48.47 61.48 48.47" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="67.67" y1="77.86" x2="49.8" y2="67.29" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="57.43 68.5 51.51 64.99 51.51 64.99" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="preview-icon-name">Commission Invoice Outward</span>
                                    </a>
                                </div><!-- .preview-icon-box -->
                            </li><!-- .col -->
                        </ul>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var agents = [];
    export default {
        name: 'createInward',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/register',
                outward_salebill: '/register/outward/salebill',
                outward_payment: '/register/outward/payment',
                outward_commission: '/register/outward/commission',
                outward_commission_invoice: '/register/outward/commissioninvoice',
            }
        },
        methods: {
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/settings/agent/update')
                        .then(( response ) => {
                            window.location.href = '/settings/agent';
                    })
                } else {
                    this.form.post('/settings/agent/create')
                        .then(( response ) => {
                            window.location.href = '/settings/agent';
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/settings/agent/fetch-agent/${this.id}`)
                    .then(response => {
                        agents = response.data;

                        this.form.id = agents.id;
                        this.form.name = agents.name;
                        this.form.pan_no = agents.pan_no;
                        this.form.gst_no = agents.gst_no;
                        this.form.default = agents.default;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>

