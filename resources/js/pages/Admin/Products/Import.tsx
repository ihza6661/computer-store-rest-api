import { BackLink } from '@/components/ui/BackLink';
import { Button } from '@/components/ui/Button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/Table';
import { Head } from '@inertiajs/react';
import { AlertCircle, CheckCircle2, Download, FileSpreadsheet, Upload, XCircle } from 'lucide-react';
import { useRef, useState } from 'react';
import AdminLayout from '../Layouts/AdminLayout';

interface ImportResult {
    row: number;
    status: 'valid' | 'success' | 'error';
    message: string;
    data: Record<string, any>;
}

interface ImportSummary {
    total: number;
    success: number;
    valid: number;
    error: number;
}

interface PreviewResponse {
    success: boolean;
    results: {
        summary: ImportSummary;
        details: ImportResult[];
    };
    message?: string;
}

interface ImportStatusResponse {
    status: 'not_found' | 'processing' | 'completed' | 'failed';
    results?: {
        summary: ImportSummary;
        details: ImportResult[];
    };
    error?: string;
}

export default function Import() {
    const [file, setFile] = useState<File | null>(null);
    const [previewing, setPreviewing] = useState(false);
    const [importing, setImporting] = useState(false);
    const [previewResults, setPreviewResults] = useState<PreviewResponse | null>(null);
    const [importJobId, setImportJobId] = useState<string | null>(null);
    const [importResults, setImportResults] = useState<ImportStatusResponse | null>(null);
    const [error, setError] = useState<string | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const selectedFile = e.target.files?.[0];
        if (selectedFile) {
            setFile(selectedFile);
            setPreviewResults(null);
            setImportResults(null);
            setError(null);
        }
    };

    const handlePreview = async () => {
        if (!file) return;

        setPreviewing(true);
        setError(null);

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/admin/products/import/preview', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || '',
                },
            });

            const data: PreviewResponse = await response.json();

            if (data.success) {
                setPreviewResults(data);
            } else {
                setError(data.message || 'Preview failed');
            }
        } catch (err) {
            setError('Failed to preview file. Please try again.');
        } finally {
            setPreviewing(false);
        }
    };

    const handleImport = async () => {
        if (!file) return;

        setImporting(true);
        setError(null);

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/admin/products/import/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || '',
                },
            });

            const data = await response.json();

            if (data.success) {
                setImportJobId(data.job_id);
                // Start polling for status
                pollImportStatus(data.job_id);
            } else {
                setError(data.message || 'Import failed to start');
                setImporting(false);
            }
        } catch (err) {
            setError('Failed to start import. Please try again.');
            setImporting(false);
        }
    };

    const pollImportStatus = async (jobId: string) => {
        const poll = async () => {
            try {
                const response = await fetch(`/admin/products/import/status/${jobId}`);
                const data: ImportStatusResponse = await response.json();

                if (data.status === 'completed') {
                    setImportResults(data);
                    setImporting(false);
                    setPreviewResults(null);
                    setFile(null);
                    if (fileInputRef.current) {
                        fileInputRef.current.value = '';
                    }
                } else if (data.status === 'failed') {
                    setError(data.error || 'Import failed');
                    setImporting(false);
                } else if (data.status === 'processing') {
                    // Continue polling
                    setTimeout(poll, 2000);
                }
            } catch (err) {
                setError('Failed to check import status');
                setImporting(false);
            }
        };

        poll();
    };

    const handleDownloadTemplate = () => {
        window.location.href = '/admin/products/import/template';
    };

    const handleReset = () => {
        setFile(null);
        setPreviewResults(null);
        setImportResults(null);
        setError(null);
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    const getStatusIcon = (status: string) => {
        switch (status) {
            case 'valid':
            case 'success':
                return <CheckCircle2 size={18} className="text-gray-700" />;
            case 'error':
                return <XCircle size={18} className="text-gray-700" />;
            default:
                return null;
        }
    };

    const errorCount = previewResults?.results.summary.error || 0;
    const hasErrors = errorCount > 0;
    const canImport = previewResults && !hasErrors && !importing;

    return (
        <AdminLayout>
            <Head title="Import Products" />

            <div className="space-y-6">
                <BackLink href="/admin/products">Back to Products</BackLink>

                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Import Products</h1>
                    <p className="mt-1 text-sm text-gray-600">Upload an Excel file to import multiple products at once</p>
                </div>

                {/* Instructions Card */}
                <Card>
                    <CardHeader>
                        <CardTitle>Instructions</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-3 text-sm text-gray-700">
                            <p>1. Download the Excel template below</p>
                            <p>2. Fill in your product data following the template format</p>
                            <p>3. Upload the completed file and preview the data</p>
                            <p>4. If validation passes, confirm to import</p>

                            <div className="mt-4 space-y-2 border-t border-gray-200 pt-4">
                                <p className="font-medium text-gray-900">Important notes:</p>
                                <ul className="list-inside list-disc space-y-1 text-gray-600">
                                    <li>Category must already exist in the database</li>
                                    <li>SKU must be unique (duplicates will be skipped)</li>
                                    <li>Image upload is not supported (add images after import)</li>
                                    <li>Maximum 500 products per import recommended</li>
                                </ul>
                            </div>

                            <Button variant="secondary" size="md" onClick={handleDownloadTemplate} className="mt-4">
                                <Download size={18} />
                                Download Template
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                {/* Upload Card */}
                <Card>
                    <CardHeader>
                        <CardTitle>Upload File</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-4">
                            <div>
                                <input
                                    ref={fileInputRef}
                                    type="file"
                                    accept=".xlsx,.xls,.csv"
                                    onChange={handleFileChange}
                                    className="block w-full text-sm text-gray-600 file:mr-4 file:rounded file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                                />
                                {file && (
                                    <p className="mt-2 text-sm text-gray-600">
                                        Selected: <span className="font-medium">{file.name}</span>
                                    </p>
                                )}
                            </div>

                            {error && (
                                <div className="flex items-start gap-3 rounded border border-gray-200 bg-gray-50 p-4">
                                    <AlertCircle size={20} className="mt-0.5 flex-shrink-0 text-gray-700" />
                                    <p className="text-sm text-gray-700">{error}</p>
                                </div>
                            )}

                            <div className="flex gap-3">
                                <Button variant="secondary" size="md" onClick={handlePreview} disabled={!file || previewing}>
                                    <FileSpreadsheet size={18} />
                                    {previewing ? 'Previewing...' : 'Preview Data'}
                                </Button>

                                {file && !previewResults && !importResults && (
                                    <Button variant="ghost" size="md" onClick={handleReset}>
                                        Reset
                                    </Button>
                                )}
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Preview Results */}
                {previewResults && (
                    <Card>
                        <CardHeader>
                            <div className="space-y-4">
                                <div>
                                    <CardTitle>Preview Results</CardTitle>
                                    <p className="mt-1 text-sm text-gray-600">
                                        Total: {previewResults.results.summary.total} | Valid: {previewResults.results.summary.valid} | Errors:{' '}
                                        {previewResults.results.summary.error}
                                    </p>
                                </div>
                                <div className="flex flex-col gap-2 sm:flex-row sm:gap-3">
                                    {canImport && (
                                        <Button variant="primary" size="md" onClick={handleImport} className="w-full sm:w-auto">
                                            <Upload size={18} />
                                            Confirm Import
                                        </Button>
                                    )}
                                    <Button variant="ghost" size="md" onClick={handleReset} className="w-full sm:w-auto">
                                        Cancel
                                    </Button>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            {hasErrors && (
                                <div className="mb-4 flex items-start gap-3 rounded border border-gray-200 bg-gray-50 p-4">
                                    <AlertCircle size={20} className="mt-0.5 flex-shrink-0 text-gray-700" />
                                    <div className="text-sm text-gray-700">
                                        <p className="font-medium">Validation errors found</p>
                                        <p className="mt-1">{errorCount} row(s) have errors. Please fix them in your Excel file and re-upload.</p>
                                    </div>
                                </div>
                            )}

                            <div className="overflow-x-auto">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Row</TableHead>
                                            <TableHead>Status</TableHead>
                                            <TableHead>Name</TableHead>
                                            <TableHead>SKU</TableHead>
                                            <TableHead>Category</TableHead>
                                            <TableHead>Message</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {previewResults.results.details.map((result) => (
                                            <TableRow key={result.row}>
                                                <TableCell>{result.row}</TableCell>
                                                <TableCell>
                                                    <div className="flex items-center gap-2">
                                                        {getStatusIcon(result.status)}
                                                        <span className="capitalize">{result.status}</span>
                                                    </div>
                                                </TableCell>
                                                <TableCell>{result.data.name || '-'}</TableCell>
                                                <TableCell>{result.data.sku || '-'}</TableCell>
                                                <TableCell>{result.data.category || '-'}</TableCell>
                                                <TableCell>
                                                    <span className={result.status === 'error' ? 'text-gray-700' : 'text-gray-600'}>
                                                        {result.message}
                                                    </span>
                                                </TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Import Progress */}
                {importing && (
                    <Card>
                        <CardContent className="py-8">
                            <div className="flex flex-col items-center justify-center space-y-4">
                                <div className="h-12 w-12 animate-spin rounded-full border-b-2 border-gray-900"></div>
                                <p className="text-sm text-gray-600">Importing products... Please wait.</p>
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Import Results */}
                {importResults && importResults.status === 'completed' && importResults.results && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Import Completed</CardTitle>
                            <p className="mt-1 text-sm text-gray-600">
                                Total: {importResults.results.summary.total} | Success: {importResults.results.summary.success} | Failed:{' '}
                                {importResults.results.summary.error}
                            </p>
                        </CardHeader>
                        <CardContent>
                            <div className="mb-4 flex items-start gap-3 rounded border border-gray-200 bg-gray-50 p-4">
                                <CheckCircle2 size={20} className="mt-0.5 flex-shrink-0 text-gray-700" />
                                <div className="text-sm text-gray-700">
                                    <p className="font-medium">Import completed successfully</p>
                                    <p className="mt-1">
                                        {importResults.results.summary.success} product(s) imported successfully.
                                        {importResults.results.summary.error > 0 && ` ${importResults.results.summary.error} row(s) failed.`}
                                    </p>
                                </div>
                            </div>

                            {importResults.results.summary.error > 0 && (
                                <div className="overflow-x-auto">
                                    <Table>
                                        <TableHeader>
                                            <TableRow>
                                                <TableHead>Row</TableHead>
                                                <TableHead>Status</TableHead>
                                                <TableHead>Name</TableHead>
                                                <TableHead>SKU</TableHead>
                                                <TableHead>Message</TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {importResults.results.details
                                                .filter((r) => r.status === 'error')
                                                .map((result) => (
                                                    <TableRow key={result.row}>
                                                        <TableCell>{result.row}</TableCell>
                                                        <TableCell>
                                                            <div className="flex items-center gap-2">
                                                                {getStatusIcon(result.status)}
                                                                <span className="capitalize">{result.status}</span>
                                                            </div>
                                                        </TableCell>
                                                        <TableCell>{result.data.name || '-'}</TableCell>
                                                        <TableCell>{result.data.sku || '-'}</TableCell>
                                                        <TableCell className="text-gray-700">{result.message}</TableCell>
                                                    </TableRow>
                                                ))}
                                        </TableBody>
                                    </Table>
                                </div>
                            )}

                            <div className="mt-4 flex gap-3">
                                <Button variant="primary" size="md" onClick={() => (window.location.href = '/admin/products')}>
                                    View Products
                                </Button>
                                <Button variant="secondary" size="md" onClick={handleReset}>
                                    Import More
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AdminLayout>
    );
}
