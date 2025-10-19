export default function FooterSection() {
    return (
        <footer className="border-t bg-background py-8">
            <div className="container mx-auto flex flex-col items-center justify-between gap-4 md:flex-row">
                <p className="text-sm text-muted-foreground">© 2025 FreshCart. Dibuat dengan cinta untuk sayuran segar.</p>
                <div className="flex gap-4">
                    <a href="#" className="text-sm text-muted-foreground hover:text-primary">
                        Privasi
                    </a>
                    <a href="#" className="text-sm text-muted-foreground hover:text-primary">
                        Ketentuan
                    </a>
                    <a href="#" className="text-sm text-muted-foreground hover:text-primary">
                        Social Media
                    </a>
                </div>
            </div>
        </footer>
    );
}
