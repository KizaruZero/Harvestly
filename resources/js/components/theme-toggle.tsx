import { Button } from '@/components/ui/button';
import { useAppearance } from '@/hooks/use-appearance';
import { Moon, Sun } from 'lucide-react';

export default function ThemeToggle() {
    const { appearance, updateAppearance } = useAppearance();

    const toggleTheme = () => {
        if (appearance === 'light') {
            updateAppearance('dark');
        } else {
            updateAppearance('light');
        }
    };

    return (
        <Button
            variant="ghost"
            size="icon"
            onClick={toggleTheme}
            className="h-9 w-9"
            title={`Switch to ${appearance === 'light' ? 'dark' : 'light'} mode`}
        >
            {appearance === 'light' ? (
                <Moon className="h-4 w-4" />
            ) : (
                <Sun className="h-4 w-4" />
            )}
            <span className="sr-only">
                {appearance === 'light'
                    ? 'Switch to dark mode'
                    : 'Switch to light mode'}
            </span>
        </Button>
    );
}
