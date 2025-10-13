import React from 'react';

const SummaryCards = ({ summary }) => {
    return (
        <div className="row mb-4">
            <div className="col-md-4">
                <div className="card border-primary">
                    <div className="card-body text-center py-2">
                        <div className="d-flex align-items-center justify-content-center">
                            <i className="bx bx-user-check bx-sm text-primary me-2"></i>
                            <span className="h5 mb-0 text-primary fw-bold">{summary.users_presensi}</span>
                        </div>
                        <small className="text-muted d-block mt-1">Users Presensi</small>
                    </div>
                </div>
            </div>
            <div className="col-md-4">
                <div className="card border-success">
                    <div className="card-body text-center py-2">
                        <div className="d-flex align-items-center justify-content-center">
                            <i className="bx bx-building bx-sm text-success me-2"></i>
                            <span className="h5 mb-0 text-success fw-bold">{summary.sekolah_presensi}</span>
                        </div>
                        <small className="text-muted d-block mt-1">Sekolah Presensi</small>
                    </div>
                </div>
            </div>
            <div className="col-md-4">
                <div className="card border-danger">
                    <div className="card-body text-center py-2">
                        <div className="d-flex align-items-center justify-content-center">
                            <i className="bx bx-user-x bx-sm text-danger me-2"></i>
                            <span className="h5 mb-0 text-danger fw-bold">{summary.guru_tidak_presensi}</span>
                        </div>
                        <small className="text-muted d-block mt-1">Guru Belum Presensi</small>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default SummaryCards;
