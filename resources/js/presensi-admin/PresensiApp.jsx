import React, { useState, useEffect } from 'react';
import SummaryCards from './components/SummaryCards';
import KabupatenSection from './components/KabupatenSection';
import UserModal from './components/UserModal';
import MadrasahModal from './components/MadrasahModal';

const PresensiApp = () => {
    const [selectedDate, setSelectedDate] = useState(new Date().toISOString().split('T')[0]);
    const [summary, setSummary] = useState({
        users_presensi: 0,
        sekolah_presensi: 0,
        guru_tidak_presensi: 0
    });
    const [kabupatenData, setKabupatenData] = useState([]);
    const [userModal, setUserModal] = useState({ isOpen: false, userId: null, userName: '' });
    const [madrasahModal, setMadrasahModal] = useState({ isOpen: false, madrasahId: null, madrasahName: '' });

    // Fetch summary data
    const fetchSummary = async (date) => {
        try {
            const response = await fetch(`/presensi-admin/summary?date=${date}`);
            const data = await response.json();
            setSummary(data);
        } catch (error) {
            console.error('Error fetching summary:', error);
        }
    };

    // Fetch presensi data
    const fetchPresensiData = async (date) => {
        try {
            const response = await fetch(`/presensi-admin/data?date=${date}`);
            const data = await response.json();
            setKabupatenData(data);
        } catch (error) {
            console.error('Error fetching presensi data:', error);
        }
    };

    // Handle date change
    const handleDateChange = (newDate) => {
        setSelectedDate(newDate);
        fetchSummary(newDate);
        fetchPresensiData(newDate);
    };

    // Handle modal events
    useEffect(() => {
        const handleOpenUserModal = (event) => {
            setUserModal({
                isOpen: true,
                userId: event.detail.userId,
                userName: event.detail.userName
            });
        };

        const handleOpenMadrasahModal = (event) => {
            setMadrasahModal({
                isOpen: true,
                madrasahId: event.detail.madrasahId,
                madrasahName: event.detail.madrasahName
            });
        };

        window.addEventListener('openUserModal', handleOpenUserModal);
        window.addEventListener('openMadrasahModal', handleOpenMadrasahModal);

        return () => {
            window.removeEventListener('openUserModal', handleOpenUserModal);
            window.removeEventListener('openMadrasahModal', handleOpenMadrasahModal);
        };
    }, []);

    // Polling effect
    useEffect(() => {
        fetchSummary(selectedDate);
        fetchPresensiData(selectedDate);

        const interval = setInterval(() => {
            fetchSummary(selectedDate);
            fetchPresensiData(selectedDate);
        }, 30000); // 30 seconds

        return () => clearInterval(interval);
    }, [selectedDate]);

    return (
        <div>
            {/* Header with date picker and export */}
            <div className="card">
                <div className="card-header d-flex justify-content-between align-items-center">
                    <h4 className="card-title mb-0">
                        <i className="bx bx-calendar me-2"></i>Data Presensi per Tanggal: {new Date(selectedDate).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })}
                    </h4>
                    <div className="d-flex align-items-center gap-2">
                        <input
                            type="date"
                            className="form-control form-control-sm"
                            value={selectedDate}
                            onChange={(e) => handleDateChange(e.target.value)}
                        />
                        <a
                            href={`/presensi-admin/export?date=${selectedDate}`}
                            className="btn btn-success btn-sm"
                        >
                            <i className="bx bx-download"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>

            {/* Summary Cards */}
            <SummaryCards summary={summary} />

            {/* Kabupaten Sections */}
            <KabupatenSection kabupatenData={kabupatenData} selectedDate={selectedDate} />

            {/* Modals */}
            <UserModal
                isOpen={userModal.isOpen}
                onClose={() => setUserModal({ isOpen: false, userId: null, userName: '' })}
                userId={userModal.userId}
                userName={userModal.userName}
            />
            <MadrasahModal
                isOpen={madrasahModal.isOpen}
                onClose={() => setMadrasahModal({ isOpen: false, madrasahId: null, madrasahName: '' })}
                madrasahId={madrasahModal.madrasahId}
                madrasahName={madrasahModal.madrasahName}
                selectedDate={selectedDate}
            />
        </div>
    );
};

export default PresensiApp;
